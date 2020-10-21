<?php


class CustomerGroup
{
    private  int $id;
    private  string $name;
    private int $parent_id;
    private int $fixed_discount;
    private int $variable_discount;

    /**
     * CustomerGroup constructor.
     * @param int $id
     * @param string $name
     * @param int $parent_id
     * @param int $fixed_discount
     * @param int $variable_discount
     */
    public function __construct(int $id, string $name, int $parent_id, int $fixed_discount, int $variable_discount)
    {
        $this->id = $id;
        $this->name = $name;
        $this->parent_id = $parent_id;
        $this->fixed_discount = $fixed_discount;
        $this->variable_discount = $variable_discount;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getParentId(): int
    {
        return $this->parent_id;
    }

    /**
     * @return int
     */
    public function getFixedDiscount(): int
    {
        return $this->fixed_discount;
    }

    /**
     * @return int
     */
    public function getVariableDiscount(): int
    {
        return $this->variable_discount;
    }


}