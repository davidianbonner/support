<?php

namespace Continuum\Support\Transformer;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Pagination\AbstractPaginator;

abstract class AbstractTransformer
{
    /**
     * Name of the json object.
     *
     * @var string
     */
    protected $name = 'data';

    public function __construct()
    {
        if (!method_exists($this, 'transform')) {
            throw new Exception(
                'A valid transform method does not exist on the Transformer for ['.get_class().']'
            );
        }
    }

    /**
     * Transform a collection.
     *
     * @param Illuminate\Support\Collection $data
     * @return array
     */
    public function collection(Collection $data): array
    {
        return [
            $this->pluraliseName() => $data->map([$this, 'transform'])
        ];
    }

    /**
     * Transform a single item.
     *
     * @param $data
     * @return array
     */
    public function item($data): array
    {
        return [
            $this->name => $this->transform($data)
        ];
    }

    /**
     * Transform a paginated item.
     *
     * @param Paginate $paginated
     * @return array
     */
    public function paginate(AbstractPaginator $paginator): array
    {
        return [
            $this->pluraliseName() => $paginator->getCollection()->map([$this, 'transform']),
            $this->pluraliseName('count') => $paginator->total()
        ];
    }

    /**
     * Make a new transformer instance.
     *
     * @param  mixed  $data
     * @param  array  $attributes
     * @return Continuum\Support\Transformer\AbstractTransformer
     */
    public static function make($data, array $attributes = []): array
    {
        $transformer = new static;

        if ($data instanceof AbstractPaginator) {
            return $transformer->paginate($data, $attributes);
        } elseif ($data instanceof Collection) {
            return $transformer->collection($data, $attributes);
        }

        return $transformer->transform($data, $attributes);
    }

    /**
     * Pluralise the sring.
     *
     * @param  string $append
     * @return string
     */
    protected function pluraliseName($append = ''): string
    {
        return str_plural($this->name).($append != '' ? "-{$append}" : '');
    }
}
