<?php

namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Book;
use Illuminate\Testing\Fluent\AssertableJson;

class BooksControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_get_books_endpoint(): void
    {
        //cria dados para o teste
        $books = Book::factory(3)->create();

        //chama a API
        $response = $this->getJson('/api/books');

        //verifica o status
        $response->assertStatus(200);

        //Verifica o total de retorno
        $response->assertJsonCount(3);

        //validações dos dados do json
        $response->assertJson(function (AssertableJson $json) use ($books) {
            //verifica os tipos de cada campo
            // $json->whereType('0.id', 'integer');
            // $json->whereType('0.title', 'string');
            // $json->whereType('0.isbn', 'string');

            //Outra forma de escrever esse teste
            $json->whereAllType([
                '0.id' => 'integer',
                '0.title' => 'string',
                '0.isbn' => 'string'
            ]);

            //verifica se os campos estão na ordem
            $json->hasAll(['0.id', '0.title', '0.isbn']);

            $book = $books->first();

            $json->whereAll([
                '0.id' => $book->id,
                '0.title' => $book->title,
                '0.isbn' => $book->isbn
            ]);
        });
    }

    public function test_get_single_books_endpoint(): void
    {
        //cria dados para o teste
        $book = Book::factory(1)->createOne();

        //chama a API
        $response = $this->getJson('/api/books/' . $book->id);
        // $response = $this->getJson('/api/books/999');

        //verifica o status
        $response->assertStatus(200);

        //validações dos dados do json
        $response->assertJson(function (AssertableJson $json) use ($book) {
            //verifica se os campos estão na ordem
            // $json->hasAll(['id', 'title', 'isbn'])->etc();
            $json->hasAll(['id', 'title', 'isbn', 'created_at', 'updated_at']);

            $json->whereAllType([
                'id' => 'integer',
                'title' => 'string',
                'isbn' => 'string'
            ]);

            $json->whereAll([
                'id' => $book->id,
                'title' => $book->title,
                'isbn' => $book->isbn
            ]);
        });
    }

    public function test_get_single_books_fail(): void
    {
        //chama a API
        $response = $this->getJson('/api/books/999');

        //verifica o status
        $response->assertStatus(404);
    }

    public function test_post_books_endpoint()
    {
        $book = Book::factory(1)->makeOne();

        $response = $this->postJson('/api/books', $book->toArray());

        $response->assertStatus(201);

        $response->assertJson(function (AssertableJson $json) use ($book) {
            $json->hasAll(['id', 'title', 'isbn', 'created_at', 'updated_at']);

            $json->whereAll([
                'title' => $book->title,
                'isbn' => $book->isbn
            ])->etc();
        });
    }

    public function test_post_books_validate_invalid_book()
    {
        $response = $this->postJson('/api/books', []);

        $response->assertStatus(422);    

        $response->assertJson(function (AssertableJson $json) {
            $json->hasAll(['message', 'errors']);
        });
    }

    public function test_put_books_endpoint()
    {
        Book::factory(1)->create();
        $book = [
            'title' => 'Teste atualizar nome livro',
            'isbn' => '1234567891011'
        ];

        $response = $this->putJson('/api/books/1', $book);

        $response->assertStatus(200);

        $response->assertJson(function (AssertableJson $json) use ($book) {
            $json->hasAll(['id', 'title', 'isbn', 'created_at', 'updated_at']);

            $json->whereAll([
                'title' => $book['title'],
                'isbn' => $book['isbn']
            ])->etc();
        });
    }

    // public function test_put_books_endpoint_fail(): void
    // {
    //     Book::factory(1)->create();
    //     $book = [
    //         'title' => 'Teste atualizar nome livro',
    //         'isbn' => '1234567891011'
    //     ];

    //     //chama a API
    //     $response = $this->putJson('/api/books/999', $book);

    //     //verifica o status
    //     $response->assertStatus(404);

    //     $response->assertJson(function (AssertableJson $json) {
    //         $json->hasAll(['message']);
    //     });
    // }

    public function test_patch_books_endpoint()
    {
        Book::factory(1)->create();

        $book = [
            'title' => 'Teste atualizar nome livro Patch'
        ];

        $response = $this->patchJson('/api/books/1', $book);

        $response->assertStatus(200);

        $response->assertJson(function (AssertableJson $json) use ($book) {
            $json->hasAll(['id', 'title', 'isbn', 'created_at', 'updated_at']);

            $json->where('title', $book['title']);
        });
    }

    public function test_delete_books_endpoint()
    {
        Book::factory(1)->createOne();

        $response = $this->deleteJson('/api/books/1');

        $response->assertStatus(204);
    }
}
