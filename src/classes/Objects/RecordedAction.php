<?php

namespace dhope0000\LXDClient\Objects;

class RecordedAction implements \JsonSerializable
{
    public function __construct(
        private readonly string $title,
        private readonly \DateTimeInterface $date,
        private readonly string $category,
        private readonly string $method
    ) {
    }

    public function __set($prop, $value)
    {
        throw new \Exception('Not allowed to set public properties on this object, use get/set/removeCustomProp', 1);
    }

    #[\Override]
    public function jsonSerialize()
    {
        return [
            'title' => $this->title,
            'date' => $this->date->format('Y-m-d H:i:s'),
        ];
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function getMethod()
    {
        return $this->method;
    }
}
