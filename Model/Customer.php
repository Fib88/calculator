<?php


class Customer
{
    private int $id;
    private string $firstName;
    private string $lastName;
    private CustomerGroup $customerGroup;
    //might need a variable for discount.

    /**
     * Customer constructor.
     * @param int $id
     * @param string $firstName
     * @param string $lastName
     * @param CustomerGroup $customerGroup
     */
    public function __construct(int $id, string $firstName, string $lastName, CustomerGroup $customerGroup)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->customerGroup = $customerGroup;
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
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return CustomerGroup
     */
    public function getCustomerGroup(): CustomerGroup
    {
        return $this->customerGroup;
    }


}
