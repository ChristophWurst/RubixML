<?php

namespace Rubix\Engine\Transformers;

use Rubix\Engine\Datasets\Dataset;

class BlanketCharacterFilter implements Transformer
{
    const SPECIAL = [
        '.', ',', '?', '!', '#', '(', ')', '[', ']', '{', '}', ':', ';', '\'', '"',
        '|', '<', '>', '/', '\\', '+', '=',
    ];

    const NUMBERS = [
        '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
    ];

    /**
     * The characters to remove from the text.
     *
     * @var array
     */
    protected $characters = [
        //
    ];

    /**
     * The column types of the fitted dataset. i.e. categorical or continuous.
     *
     * @var array
     */
    protected $columnTypes = [
        //
    ];

    /**
     * @param  array  $characters
     * @return void
     */
    public function __construct(array $characters = [])
    {
        $this->characters = $characters;
    }

    /**
     * @param  \Rubix\Engine\Datasets\Dataset  $dataset
     * @return void
     */
    public function fit(Dataset $dataset) : void
    {
        $this->columnTypes = $dataset->columnTypes();
    }

    /**
     * Normalize the dataset.
     *
     * @param  array  $samples
     * @return void
     */
    public function transform(array &$samples) : void
    {
        foreach ($samples as &$sample) {
            foreach ($this->columnTypes as $column => $type) {
                if ($type === self::CATEGORICAL) {
                    $sample[$column] = str_replace($this->characters, '', $sample[$column]);
                }
            }
        }
    }
}
