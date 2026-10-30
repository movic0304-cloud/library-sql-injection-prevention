<?php
declare(strict_types=1);
final class Library {
    public function __construct(private PDO $db) {}
    public function addBook(string $title, string $author, string $isbn): int {
        $stmt = $this->db->prepare('INSERT INTO books(title, author, isbn) VALUES (:title, :author, :isbn)');
        $stmt->execute([':title' => $title, ':author' => $author, ':isbn' => $isbn]);
        return (int)$this->db->lastInsertId();
    }
    public function searchBooks(string $query): array {
        $stmt = $this->db->prepare('SELECT id, title, author, isbn FROM books WHERE title LIKE :q OR author LIKE :q2 ORDER BY title');
        $term = '%' . str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $query) . '%';
        $stmt = $this->db->prepare("SELECT id, title, author, isbn FROM books WHERE title LIKE :q ESCAPE '\\' OR author LIKE :q2 ESCAPE '\\' ORDER BY title");
        $stmt->execute([':q' => $term, ':q2' => $term]);
        return $stmt->fetchAll();
    }
    public function findBook(int $id): ?array {
        $stmt = $this->db->prepare('SELECT id, title, author, isbn FROM books WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }
    public function addMember(string $name, string $email): int {
        $stmt = $this->db->prepare('INSERT INTO members(name, email) VALUES (:name, :email)');
        $stmt->execute([':name' => $name, ':email' => $email]);
        return (int)$this->db->lastInsertId();
    }
    public function borrowBook(int $bookId, int $memberId): int {
        $stmt = $this->db->prepare('INSERT INTO loans(book_id, member_id) VALUES (:book, :member)');
        $stmt->execute([':book' => $bookId, ':member' => $memberId]);
        return (int)$this->db->lastInsertId();
    }
    public function memberLoans(int $memberId): array {
        $stmt = $this->db->prepare('SELECT loans.id, books.title, loans.borrowed_at, loans.returned_at FROM loans JOIN books ON books.id = loans.book_id WHERE loans.member_id = :member ORDER BY loans.id');
        $stmt->execute([':member' => $memberId]);
        return $stmt->fetchAll();
    }
}
