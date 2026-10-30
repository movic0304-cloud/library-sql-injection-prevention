<?php
declare(strict_types=1);
require __DIR__ . '/../src/bootstrap.php';
require __DIR__ . '/../src/Library.php';
$db = database();
initialize($db);
$library = new Library($db);
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $title = trim((string)($_POST['title'] ?? ''));
        $author = trim((string)($_POST['author'] ?? ''));
        $isbn = trim((string)($_POST['isbn'] ?? ''));
        if ($title === '' || $author === '' || $isbn === '') throw new InvalidArgumentException('All fields are required.');
        $library->addBook($title, $author, $isbn);
        header('Location: /?added=1', true, 303);
        exit;
    } catch (Throwable $e) { $error = 'Unable to add book. Check required fields and unique ISBN.'; }
}
$q = (string)($_GET['q'] ?? '');
$books = $library->searchBooks($q);
function h(string $value): string { return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Secure Library</title><style>body{font:16px system-ui;max-width:780px;margin:3rem auto;padding:0 1rem;color:#202530}input,button{padding:.6rem;margin:.2rem}table{border-collapse:collapse;width:100%}td,th{border-bottom:1px solid #ddd;text-align:left;padding:.6rem}section{margin:2rem 0}</style></head><body>
<h1>Secure Library Management</h1><p>Reconstructed demonstration of parameterized SQL queries with PHP PDO.</p>
<?php if ($error): ?><p role="alert"><?= h($error) ?></p><?php endif; ?>
<?php if (isset($_GET['added'])): ?><p>Book added.</p><?php endif; ?>
<section><h2>Search books</h2><form method="get"><input name="q" aria-label="Search title or author" value="<?= h($q) ?>"><button>Search</button></form>
<table><thead><tr><th>Title</th><th>Author</th><th>ISBN</th></tr></thead><tbody><?php foreach ($books as $book): ?><tr><td><?= h($book['title']) ?></td><td><?= h($book['author']) ?></td><td><?= h($book['isbn']) ?></td></tr><?php endforeach; ?></tbody></table></section>
<section><h2>Add a book</h2><form method="post"><input name="title" placeholder="Title" required maxlength="200"><input name="author" placeholder="Author" required maxlength="200"><input name="isbn" placeholder="ISBN" required maxlength="32"><button>Add</button></form></section>
</body></html>
