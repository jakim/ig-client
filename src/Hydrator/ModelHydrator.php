<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 01/05/2020
 */

namespace Jakim\Hydrator;


use Jakim\Helper\ArrayHelper;
use Jakim\Map\MapInterface;
use Jakim\Model\ModelInterface;

class ModelHydrator
{
    protected array $map;

    public function __construct(array $map)
    {
        $this->map = $map;
    }

    public function hydrate(ModelInterface $model, array $data): ModelInterface
    {
        $properties = $this->getProperties($model);
        $data = $this->normalizeData($data);

        foreach ($properties as $property => $value) {
            $propertyMap = ArrayHelper::getValue($this->map, sprintf('%s.%s', MapInterface::PROPERTIES, $property));

            // is map of related model
            if (is_array($propertyMap)) {
                $related = ArrayHelper::getValue($propertyMap, MapInterface::MODEL);

                if (ArrayHelper::getValue($propertyMap, MapInterface::MULTIPLE, false)) {
                    $relatedData = ArrayHelper::getValue($data, $propertyMap[MapInterface::ENVELOPE]);
                    unset($propertyMap[MapInterface::ENVELOPE]);

                    foreach ($relatedData as $item) {
                        $model->$property[] = (new static($propertyMap))->hydrate(new $related(), $item);
                    }
                } else {
                    $model->$property = (new static($propertyMap))->hydrate(new $related(), $data);
                }
            } else {
                $model->$property = ArrayHelper::getValue($data, $propertyMap, $value ?? null);
            }

        }

        return $model;
    }

    /**
     * @param \Jakim\Model\ModelInterface $model
     * @return array
     */
    private function getProperties(ModelInterface $model): array
    {
        $properties = get_object_vars($model);

        return $properties;
    }

    /**
     * @param array $data
     * @return mixed|null
     */
    private function normalizeData(array $data)
    {
        $envelope = ArrayHelper::getValue($this->map, MapInterface::ENVELOPE);
        if ($envelope) {
            $data = ArrayHelper::getValue($data, $envelope);
        }

        return $data;
    }
}