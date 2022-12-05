<?php

namespace dhope0000\LXDClient\Objects;

class RecordedAction implements \JsonSerializable
{
    private string $title;
    private DateTimeInterface $date;
    private string $category;
    private string $method;

    public function __construct(string $title, \DateTimeInterface $date, string $category, string $method)
    {
        $this->title = $title;
        $this->date = $date;
        $this->category = $category;
        $this->method = $method;
    }

    public function __set(string $prop, $value)
    {
        throw new \Exception("Not allowed to set public properties on this object, use get/set/removeCustomProp", 1);
    }

    public function jsonSerialize() :array
    {
        return [
            "title"=>$this->title,
            "date"=>$this->date->format("Y-m-d H:i:s")
        ];
    }

    public function getTitle() :string
    {
        return $this->title;
    }

    public function getCategory() :string
    {
        return $this->category;
    }

    public function getMethod() :string
    {
        return $this->method;
    }
}
