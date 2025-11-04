<?php

namespace Elementalys\Controllers;

use Elementalys\Database\Connection;
use PDO;

class RecipeController extends BaseController
{
    public function categories(): array
    {
        $pdo = Connection::getInstance();

        return $pdo->query('SELECT id, name, description FROM product_categories ORDER BY name')->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createCategory(array $data): array
    {
        $name = $this->sanitizeString($data['name'] ?? '');
        $description = $this->sanitizeString($data['description'] ?? '');

        if ($name === '') {
            return [
                'success' => false,
                'message' => 'Informe um nome para a categoria.',
            ];
        }

        $pdo = Connection::getInstance();
        $statement = $pdo->prepare('INSERT INTO product_categories (name, description) VALUES (:name, :description)');
        $statement->execute([
            'name' => $name,
            'description' => $description,
        ]);

        return [
            'success' => true,
            'message' => 'Categoria criada e disponível para produtos e receitas.',
        ];
    }

    public function create(array $data): array
    {
        $name = $this->sanitizeString($data['name'] ?? '');
        $summary = $this->sanitizeString($data['summary'] ?? '');
        $ingredients = $this->sanitizeLongText($data['ingredients'] ?? '');
        $instructions = $this->sanitizeLongText($data['instructions'] ?? '');
        $preparationTime = $this->sanitizeString($data['preparation_time'] ?? '');
        $yield = $this->sanitizeString($data['yield_description'] ?? '');
        $imagePath = $this->sanitizeUrl($data['image_path'] ?? '');
        $categoryId = $this->sanitizeNullableInt($data['product_category_id'] ?? null);

        if ($name === '' || $instructions === '') {
            return [
                'success' => false,
                'message' => 'Informe o nome da receita e o modo de preparo.',
            ];
        }

        $pdo = Connection::getInstance();
        $statement = $pdo->prepare('INSERT INTO recipes (product_category_id, name, summary, ingredients, instructions, preparation_time, yield_description, image_path) VALUES (:category, :name, :summary, :ingredients, :instructions, :preparation_time, :yield_description, :image_path)');
        $statement->execute([
            'category' => $categoryId,
            'name' => $name,
            'summary' => $summary,
            'ingredients' => $ingredients,
            'instructions' => $instructions,
            'preparation_time' => $preparationTime,
            'yield_description' => $yield,
            'image_path' => $imagePath,
        ]);

        return [
            'success' => true,
            'message' => 'Receita cadastrada com sucesso.',
        ];
    }

    public function groupedByCategory(): array
    {
        $pdo = Connection::getInstance();

        $categories = $this->categories();
        $grouped = [];

        foreach ($categories as $category) {
            $grouped[$category['id']] = [
                'id' => (int) $category['id'],
                'name' => $category['name'],
                'description' => $category['description'],
                'recipes' => [],
            ];
        }

        $grouped[0] = [
            'id' => 0,
            'name' => 'Sem categoria',
            'description' => 'Receitas ainda não categorizadas.',
            'recipes' => [],
        ];

        $query = 'SELECT r.*, c.name AS category_name FROM recipes r LEFT JOIN product_categories c ON c.id = r.product_category_id ORDER BY COALESCE(c.name, "Sem categoria"), r.name';
        $recipes = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($recipes as $recipe) {
            $categoryId = $recipe['product_category_id'] ? (int) $recipe['product_category_id'] : 0;
            $grouped[$categoryId]['recipes'][] = $recipe;
        }

        return array_values($grouped);
    }

    public function forSelect(): array
    {
        $pdo = Connection::getInstance();

        return $pdo->query('SELECT id, name FROM recipes ORDER BY name')->fetchAll(PDO::FETCH_ASSOC);
    }
}
