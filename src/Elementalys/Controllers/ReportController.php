<?php

namespace Elementalys\Controllers;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use Elementalys\Database\Connection;
use PDO;

class ReportController extends BaseController
{
    public function normalizeRange(?string $start, ?string $end): array
    {
        $defaultEnd = new DateTimeImmutable('today');
        $defaultStart = $defaultEnd->sub(new DateInterval('P3M'));

        $startDate = $this->parseDate($start) ?? $defaultStart;
        $endDate = $this->parseDate($end) ?? $defaultEnd;
        $hasError = false;
        $message = '';

        if ($startDate > $endDate) {
            $hasError = true;
            $message = 'A data inicial não pode ser maior que a data final.';
            $startDate = $defaultStart;
            $endDate = $defaultEnd;
        }

        return [
            'start' => $startDate->setTime(0, 0, 0),
            'end' => $endDate->setTime(23, 59, 59),
            'hasError' => $hasError,
            'message' => $message,
        ];
    }

    public function build(DateTimeInterface $start, DateTimeInterface $end): array
    {
        return [
            'summary' => $this->summary($start, $end),
            'monthly' => $this->monthlyRevenue($start, $end),
            'topProducts' => $this->topProducts($start, $end),
            'topCustomers' => $this->topCustomers($start, $end),
        ];
    }

    private function parseDate(?string $value): ?DateTimeImmutable
    {
        if (! $value) {
            return null;
        }

        $date = DateTimeImmutable::createFromFormat('Y-m-d', $value);

        if (! $date) {
            return null;
        }

        return $date;
    }

    private function summary(DateTimeInterface $start, DateTimeInterface $end): array
    {
        $pdo = Connection::getInstance();
        $query = 'SELECT 
                COUNT(*) AS total_sales,
                IFNULL(SUM(quantity), 0) AS total_items,
                IFNULL(SUM(total_cost), 0) AS total_cost,
                IFNULL(SUM(total_price), 0) AS total_revenue,
                IFNULL(AVG(total_price), 0) AS average_ticket
            FROM sales
            WHERE created_at BETWEEN :start AND :end';
        $statement = $pdo->prepare($query);
        $statement->execute([
            'start' => $start->format('Y-m-d H:i:s'),
            'end' => $end->format('Y-m-d H:i:s'),
        ]);

        $summary = $statement->fetch(PDO::FETCH_ASSOC) ?: [];
        $summary['total_sales'] = (int) ($summary['total_sales'] ?? 0);
        $summary['total_items'] = (int) ($summary['total_items'] ?? 0);
        $summary['total_cost'] = (float) ($summary['total_cost'] ?? 0);
        $summary['total_revenue'] = (float) ($summary['total_revenue'] ?? 0);
        $summary['average_ticket'] = (float) ($summary['average_ticket'] ?? 0);
        $summary['total_profit'] = $summary['total_revenue'] - $summary['total_cost'];

        return $summary;
    }

    private function monthlyRevenue(DateTimeInterface $start, DateTimeInterface $end): array
    {
        $pdo = Connection::getInstance();
        $query = 'SELECT DATE_FORMAT(created_at, "%Y-%m") AS period,
                IFNULL(SUM(total_price), 0) AS revenue,
                IFNULL(SUM(total_cost), 0) AS cost,
                IFNULL(SUM(quantity), 0) AS items
            FROM sales
            WHERE created_at BETWEEN :start AND :end
            GROUP BY YEAR(created_at), MONTH(created_at)
            ORDER BY YEAR(created_at), MONTH(created_at)';
        $statement = $pdo->prepare($query);
        $statement->execute([
            'start' => $start->format('Y-m-d H:i:s'),
            'end' => $end->format('Y-m-d H:i:s'),
        ]);

        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        return array_map(static function (array $row): array {
            $row['revenue'] = (float) $row['revenue'];
            $row['cost'] = (float) $row['cost'];
            $row['items'] = (int) $row['items'];
            $row['profit'] = $row['revenue'] - $row['cost'];

            return $row;
        }, $rows);
    }

    private function topProducts(DateTimeInterface $start, DateTimeInterface $end, int $limit = 5): array
    {
        $pdo = Connection::getInstance();
        $query = 'SELECT p.name,
                IFNULL(SUM(s.quantity), 0) AS total_quantity,
                IFNULL(SUM(s.total_price), 0) AS total_revenue,
                IFNULL(SUM(s.total_price - s.total_cost), 0) AS total_profit
            FROM sales s
            INNER JOIN products p ON p.id = s.product_id
            WHERE s.created_at BETWEEN :start AND :end
            GROUP BY p.id, p.name
            ORDER BY total_quantity DESC
            LIMIT ' . (int) $limit;
        $statement = $pdo->prepare($query);
        $statement->execute([
            'start' => $start->format('Y-m-d H:i:s'),
            'end' => $end->format('Y-m-d H:i:s'),
        ]);

        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        return array_map(static function (array $row): array {
            $row['total_quantity'] = (int) $row['total_quantity'];
            $row['total_revenue'] = (float) $row['total_revenue'];
            $row['total_profit'] = (float) $row['total_profit'];

            return $row;
        }, $rows);
    }

    private function topCustomers(DateTimeInterface $start, DateTimeInterface $end, int $limit = 5): array
    {
        $pdo = Connection::getInstance();
        $query = 'SELECT COALESCE(c.name, "Consumidor final") AS name,
                IFNULL(COUNT(s.id), 0) AS orders,
                IFNULL(SUM(s.total_price), 0) AS total_revenue
            FROM sales s
            LEFT JOIN customers c ON c.id = s.customer_id
            WHERE s.created_at BETWEEN :start AND :end
            GROUP BY c.id, c.name
            ORDER BY total_revenue DESC
            LIMIT ' . (int) $limit;
        $statement = $pdo->prepare($query);
        $statement->execute([
            'start' => $start->format('Y-m-d H:i:s'),
            'end' => $end->format('Y-m-d H:i:s'),
        ]);

        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        return array_map(static function (array $row): array {
            $row['orders'] = (int) $row['orders'];
            $row['total_revenue'] = (float) $row['total_revenue'];

            return $row;
        }, $rows);
    }
}
