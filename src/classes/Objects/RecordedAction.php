<?php

namespace dhope0000\LXDClient\Objects;

class RecordedAction implements \JsonSerializable
{
    private $title;
    private $date;
    private $category;
    private $method;

    public function __construct(string $title, \DateTimeInterface $date, string $category, string $method)
    {
        $this->title = $title;
        $this->date = $date;
        $this->category = $category;
        $this->method = $method;
    }

    public function __set($prop, $value)
    {
        throw new \Exception("Not allowed to set public properties on this object, use get/set/removeCustomProp", 1);
    }

    public function jsonSerialize()
    {
        return [
            "title"=>$this->title,
            "date"=>$this->date->format("Y-m-d H:i:s")
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
