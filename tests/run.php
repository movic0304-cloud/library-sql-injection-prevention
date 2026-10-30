<?php
declare(strict_types=1);
require __DIR__ . '/../src/bootstrap.php';
require __DIR__ . '/../src/Library.php';
$db = database(':memory:');
initialize($db);
$lib = new Library($db);
$passed = 0;
function check(bool $condition, string $name): void { global $passed; if (!$condition) { fwrite(STDERR, "FAIL: $name\n"); exit(1); } $passed++; echo "PASS: $name\n"; }
$id = $lib->addBook('Clean Code', 'Robert Martin', 'ISBN-1');
check($id === 1, 'book creation');
check(count($lib->searchBooks('Clean')) === 1, 'title search');
check(count($lib->searchBooks('Robert')) === 1, 'author search');
check(count($lib->searchBooks('missing')) === 0, 'no match');
check(count($lib->searchBooks("' OR 1=1 --")) === 0, 'SQL injection OR payload');
check(count($lib->searchBooks("'; DROP TABLE books; --")) === 0, 'SQL injection DROP payload');
check(count($lib->searchBooks("' UNION SELECT 1,2,3,4 --")) === 0, 'SQL injection UNION payload');
check(count($lib->searchBooks('%')) === 0, 'percent treated literally');
check(count($lib->searchBooks('_')) === 0, 'underscore treated literally');
check($lib->findBook($id)['title'] === 'Clean Code', 'record lookup');
check($lib->findBook(999) === null, 'missing record');
$lib->addBook("A 'quoted' title", 'Tester', 'ISBN-2');
check(count($lib->searchBooks("'quoted'")) === 1, 'quotes in legitimate title');
$member = $lib->addMember('Alex', 'alex@example.com');
check($member === 1, 'member creation');
$loan = $lib->borrowBook($id, $member);
check($loan === 1, 'loan creation');
check(count($lib->memberLoans($member)) === 1, 'member loan lookup');
echo "$passed/15 checks passed\n";
