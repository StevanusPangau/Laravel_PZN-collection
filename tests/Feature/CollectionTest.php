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
    /**
     * push(data) = Menambah data ke paling belakang
     * pop() = Menghapus dan mengambil data paling terakhir
     * prepend(data) = Menambah data ke paling depan
     * pull(key) = Menghapus dan mengambil data sesuai dengan key
     * put(key, data) = Mengubah data dengan key
     */
    function testCrud()
    {
        $collectioon = collect([]);
        $collectioon->push(1, 2, 3);
        assertEqualsCanonicalizing([1, 2, 3], $collectioon->all());

        $result = $collectioon->pop();
        assertEqualsCanonicalizing([1, 2], $collectioon->all());
    }

    /**
     * # MAPPING
     * map(function) = Iterasi seluruh data, dan mengirim seluruh data ke function
     * mapInto(class) = Iterasi seluruh data, dan membuat object baru untuk class dengan mengirim parameter tiap data
     * mapSpread(function) = Iterasi seluruh data, dan mengirim tiap data sebagai parameter di function
     * mapToGroups(function) = Iterasi seluruh data, dan mengirim tiap data ke function, function harus mengembalikan single key-value array untuk di group sebagai collection baru
     */
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

    /**
     * # ZIPPING
     * zip(collection/array) = Menggabungkan tiap item di collection sehingga menjadi collection baru
     * concat(collection/array) = Menambahkan collection pada bagian akhir sehingga menjadi collection baru
     * combine(collection/array) = Menggabungkan collection sehingga collection pertama menjadi key dan collection kedua menjadi value
     */
    function testZip()
    {
        $collection1 = collect([1, 2, 3]);
        $collection2 = collect([4, 5, 6]);
        $collection3 = $collection1->zip($collection2);

        assertEquals([
            collect([1, 4]),
            collect([2, 5]),
            collect([3, 6]),
        ], $collection3->all());
    }

    function testConcat()
    {
        $collection1 = collect([1, 2, 3]);
        $collection2 = collect([4, 5, 6]);
        $collection3 = $collection1->concat($collection2);

        assertEquals([1, 2, 3, 4, 5, 6], $collection3->all());
    }

    function testCombine()
    {
        $collection1 = collect(["name", "country"]);
        $collection2 = collect(["Evan", "Indonesia"]);
        $collection3 = $collection1->combine($collection2);

        assertEquals([
            "name" => "Evan",
            "country" => "Indonesia"
        ], $collection3->all());
    }

    /**
     * #FLATTENING
     * collapse() = Mengubah tiap array di item collection menjadi flat collection
     * flatMap(function) = Iterasi tiap data, dikirim ke function yang menghasilkan collection, dan diubah menjadi flat collection
     */
    function testCollapse()
    {
        $collection = collect([
            [1, 2, 3],
            [4, 5, 6],
            [7, 8, 9]
        ]);
        $result = $collection->collapse();
        assertEqualsCanonicalizing([1, 2, 3, 4, 5, 6, 7, 8, 9], $result->all());
    }

    function testFlatMap()
    {
        $collection = collect([
            [
                "name" => "Evan",
                "hobbies" => ["Coding", "Stream"]
            ],
            [
                "name" => "Stevanus",
                "hobbies" => ["Music", "Gaming"]
            ]
        ]);
        $hobbies = $collection->flatMap(function ($item) {
            return $item["hobbies"];
        });
        assertEquals(["Coding", "Stream", "Music", "Gaming"], $hobbies->all());
    }
}
