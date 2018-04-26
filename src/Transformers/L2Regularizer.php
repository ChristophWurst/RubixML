<?php

namespace Rubix\Engine\Transformers;

use Rubix\Engine\Datasets\Dataset;

class L2Regularizer implements Transformer
{
    /**
     * The columns that should be regularized. i.e. the continuous data points.
     *
     * @var array
     */
    protected $columns = [
        //
    ];

    /**
     * @return array
     */
    public function columns() : array
    {
        return $this->columns;
    }

    /**
     * Determine the columns that need to be regularized.
     *
     * @param  \Rubix\Engine\Datasets\Dataset  $dataset
     * @return void
     */
    public function fit(Dataset $dataset) : void
    {
        $this->columns = [];

        foreach ($dataset->columnTypes() as $column => $type) {
            if ($type === self::CONTINUOUS) {
                $this->columns[] = $column;
            }
        }
    }

    /**
     * Regularize the dataset by dividing each feature by the L2 norm of the sample
     * vector.
     *
     * @param  array  $samples
     * @return void
     */
    public function transform(array &$samples) : void
    {
        foreach ($samples as &$sample) {
            $norm = sqrt(array_reduce($this->columns, function ($carry, $column) use ($sample) {
                return $carry += $sample[$column] ** 2;
            }, 0));

            foreach ($this->columns as $column) {
                $sample[$column] /= ($norm ? $norm : self::EPSILON);
            }
        }
    }
}
