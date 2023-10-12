<?php

namespace App\Repositories\Eloquent;

use App\Models\Genre as Model;
use App\Repositories\Presenters\PaginationPresenters;
use Core\Domain\Entity\Genre as GenreEntity;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;
use Core\Domain\ValueObject\Uuid;
use DateTime;

class GenreEloquentRepository implements GenreRepositoryInterface
{
    protected $model;

    public function __construct(Model $genre)
    {
        $this->model = $genre;
    }

    public function insert(GenreEntity $genre): GenreEntity
    {
        $genreDb = $this->model->create(
            [
                'id' => $genre->id(),
                'name' => $genre->name,
                'is_active' => $genre->isActive,
                'created_at' => $genre->createdAt(),
            ]
        );

        if (count($genre->categoriesId) > 0) {
            [
                $genreDb->categories()->sync($genre->categoriesId),
            ];
        }

        return $this->toGenre($genreDb);

    }

    public function findById(string $genreId): GenreEntity
    {
        if (!$genreDb = $this->model->find($genreId)) {
            throw new NotFoundException("Genre {$genreId} Not Found!");
        }

        return $this->toGenre($genreDb);
    }

    public function getIdsListIds(array $genresId = []): array
    {
        return $this->model
            ->whereIn('id', $genresId)
            ->pluck('id')
            ->toArray();
    }

    public function findAll(string $filter = '', $order = 'DESC'): array
    {
        $genres = $this->model
            ->where(function ($query) use ($filter) {
                if ($filter) {
                    $query->where('name', 'LIKE', "%{$filter}%");
                }
            })->orderBy('id', $order)->get();

        return $genres->toArray();
    }

    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, int $totalPage = 15): PaginationInterface
    {
        $result = $this->model
            ->where(function ($query) use ($filter) {
                if ($filter) {
                    $query->where('name', 'LIKE', "%{$filter}%");
                }
            })->orderBy('name', $order)
            ->paginate($totalPage);

        return new PaginationPresenters($result);

    }

    public function update(GenreEntity $genre): GenreEntity
    {
        if (!$genreDb = $this->model->find($genre->id)) {
            throw new NotFoundException('Genre não encontrado');
        }

        $genreDb->update([
            'name' => $genre->name,
            'is_active' => $genre->isActive,
        ]);

        $genreDb->refresh();

        return $this->toGenre($genreDb);
    }

    public function delete(string $genreId): bool
    {
        if (!$genreDb = $this->model->find($genreId)) {
            throw new NotFoundException('Genre não encontrado');
        }

        return $genreDb->delete();
    }

    private function toGenre(Model $object): GenreEntity
    {
        //dd($object->createdAt);
        /** @var GenreEntity $genre */
        $entity = new GenreEntity(
            id:new Uuid($object->id),
            name:$object->name,
            createdAt:new DateTime($object->created_at)
        );

        ((bool) $object->is_active) ? $entity->activate() : $entity->deactivate();

        return $entity;
    }

}
