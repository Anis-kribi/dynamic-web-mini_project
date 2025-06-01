<?php
require_once("../fragments_pages/header.php");
require_once("../fragments_pages/navbar.php");
require_once("../dbconnect.php");

// Sample book data (This would normally come from the database)
$books = [
    [
        "book_id" => 1,
        "title" => "A Study in Scarlet",
        "author_id" => 1,
        "category_id" => 1,
        "price" => 9.99,
        "description" => "The first novel featuring Sherlock Holmes, where he investigates a crime.",
        "cover_image" => "../assets/img/books/cover_study_in_scarlet.jpg",
        "created_at" => "2025-04-22 21:10:02",
        "publication_year" => 1887,
        "rating" => 4.5
    ],
    [
        "book_id" => 2,
        "title" => "The Sign of the Four",
        "author_id" => 1,
        "category_id" => 1,
        "price" => 12.99,
        "description" => "Sherlock Holmes solves a case involving a stolen treasure and a complex conspiracy.",
        "cover_image" => "../assets/img/books/cover_sign_of_four.jpg",
        "created_at" => "2025-04-22 21:10:02",
        "publication_year" => 1890,
        "rating" => 4.0
    ],
    [
        "book_id" => 3,
        "title" => "The Hound of the Baskervilles",
        "author_id" => 1,
        "category_id" => 1,
        "price" => 14.99,
        "description" => "One of the most famous Sherlock Holmes novels, where he investigates the legend of a ghostly hound.",
        "cover_image" => "../assets/img/books/cover_hound_of_baskervilles.jpg",
        "created_at" => "2025-04-22 21:10:02",
        "publication_year" => 1902,
        "rating" => 4.8
    ],
    [
        "book_id" => 4,
        "title" => "The Valley of Fear",
        "author_id" => 1,
        "category_id" => 1,
        "price" => 13.99,
        "description" => "Holmes investigates a secret society and a murder mystery, leading to a chilling discovery.",
        "cover_image" => "../assets/img/books/cover_valley_of_fear.jpg",
        "created_at" => "2025-04-22 21:10:02",
        "publication_year" => 1915,
        "rating" => 4.2
    ],
    [
        "book_id" => 5,
        "title" => "Sherlock Holmes: Le Signe des Quatre",
        "author_id" => 1,
        "category_id" => 1,
        "price" => 12.99,
        "description" => "The French version of The Sign of the Four, following the famous detective's journey through a complex mystery.",
        "cover_image" => "../assets/img/books/sherlock4.jpg",
        "created_at" => "2025-04-22 23:56:50",
        "publication_year" => 1890,
        "rating" => 4.7
    ],
    [
        "book_id" => 6,
        "title" => "Attack on Titan - Tome 1",
        "author_id" => 3,
        "category_id" => 2,
        "price" => 9.50,
        "description" => "The first volume of the famous manga series 'Attack on Titan', where humanity fights against giant creatures.",
        "cover_image" => "../assets/img/books/aot1.jpg",
        "created_at" => "2025-04-22 23:56:50",
        "publication_year" => 2010,
        "rating" => 4.9
    ],
    [
        "book_id" => 7,
        "title" => "Attack on Titan - Tome 2",
        "author_id" => 3,
        "category_id" => 2,
        "price" => 9.50,
        "description" => "The second volume of 'Attack on Titan', continuing the thrilling struggle for survival against the Titans.",
        "cover_image" => "../assets/img/books/aot2.jpg",
        "created_at" => "2025-04-22 23:56:50",
        "publication_year" => 2010,
        "rating" => 4.8
    ],
    [
        "book_id" => 8,
        "title" => "Harry Potter et la Chambre des Secrets",
        "author_id" => 3,
        "category_id" => 3,
        "price" => 15.00,
        "description" => "In the second book of the Harry Potter series, Harry must uncover the mystery of the Chamber of Secrets.",
        "cover_image" => "../assets/img/books/harrypotter2.jpg",
        "created_at" => "2025-04-22 23:56:50",
        "publication_year" => 1998,
        "rating" => 4.6
    ]
];

foreach ($books as $book) {
    // Insert the book into the database
    $sql = "INSERT INTO books (book_id, title, author_id, category_id, price, description, cover_image, created_at, publication_year, rating) 
            VALUES (:book_id, :title, :author_id, :category_id, :price, :description, :cover_image, :created_at, :publication_year, :rating)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':book_id' => $book['book_id'],
        ':title' => $book['title'],
        ':author_id' => $book['author_id'],
        ':category_id' => $book['category_id'],
        ':price' => $book['price'],
        ':description' => $book['description'],
        ':cover_image' => $book['cover_image'],
        ':created_at' => $book['created_at'],
        ':publication_year' => $book['publication_year'],
        ':rating' => $book['rating']
    ]);
}
?>

