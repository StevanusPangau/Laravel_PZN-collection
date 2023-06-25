<?php

namespace Tests\Feature;

use App\Data\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertEqualsCanonicalizing;

class CollectionTest extends TestCase
{
    public function testCreateCollection()
    {
        $collection = collect([1, 2, 3]);
        assertEqualsCanonicalizing([1, 2, 3], $collection->all());
    }

    // Foreach
    public function testForEach()
    {
        $collection = collect([1, 2, 3, 4, 5, 6, 7, 8, 9]);
        foreach ($collection as $key => $value) {
            assertEquals($key + 1, $value);
        }
    }

    // Manipulasi-Collection
    function testCrud()
    {
        $collectioon = collect([]);
        $collectioon->push(1, 2, 3);
        assertEqualsCanonicalizing([1, 2, 3], $collectioon->all());

        $result = $collectioon->pop();
        assertEqualsCanonicalizing([1, 2], $collectioon->all());
    }

    // Mapping (untuk konversi dari satu data ke data yang lain)
    function testMap()
    {
        $collection = collect([1, 2, 3]);
        $result = $collection->map(function ($item) {
            return $item * 2;
        });
        assertEqualsCanonicalizing([2, 4, 6], $result->all());
    }

    function testMapInto()
    {
        $collection = collect(["Evan"]);
        $result = $collection->mapInto(Person::class);
        assertEquals([new Person("Evan")], $result->all());
    }

    // untuk memecah collection array sebagai parameter
    function testMapSpread()
    {
        $collection = collect([
            ["Evan", "Pangau"],
            ["Stevanus", "Evan"]
        ]);

        $result = $collection->mapSpread(function ($firstName, $lastName) {
            $fullName = $firstName . " " . $lastName;
            return new Person($fullName);
        });

        assertEquals([
            new Person("Evan Pangau"),
            new Person("Stevanus Evan")
        ], $result->all());
    }

    // cara mengabungkan data dengan mapping pakai MapToGroups
    function testMapToGroups()
    {
        $collection = collect([
            [
                "name" => "Evan",
                "department" => "IT"
            ],
            [
                "name" => "Stevanus",
                "department" => "HR"
            ],
            [
                "name" => "Pangau",
                "department" => "IT"
            ]
        ]);

        $result = $collection->mapToGroups(function ($person) {
            return [
                $person["department"] => $person["name"]
            ];
        });

        assertEquals([
            "IT" => collect(["Evan", "Pangau"]),
            "HR" => collect(["Stevanus"])
        ], $result->all());
    }
}
