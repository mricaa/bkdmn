<?php
include $_SERVER['DOCUMENT_ROOT'] . '/ADMIN/utility/DBConnection.php';

class Book {
    public $conn;

    public function __construct() {
        $db = new DBConnection();
        $this->conn = $db->conn;
    }

    // Method to save a book (add or update)
    public function saveBook($post) {
        // Check if bookId is provided for an update operation
        $bookId = isset($post['bookId']) ? $post['bookId'] : '';

        // Extract book details from POST data
        $bookCategory = $post['bookCategory'];
        $Title = $post['Title'];
        $Author = $post['Author'];
        $columnNumber = $post['columnNumber'];
        $Accession = $post['Accession'];
        $bookEdition = $post['bookEdition'];
        $bookYear = $post['bookYear'];
        $Property = $post['Property'];
        $isbn = $post['isbn'];

        // Determine if updating an existing record or adding a new one
        if (!empty($bookId)) {
            // Update existing book record
            $sql = "UPDATE book SET bookCategory='$bookCategory', Title='$Title', Author='$Author', columnNumber='$columnNumber', Accession='$Accession', bookEdition='$bookEdition', bookYear='$bookYear', Property='$Property', ISBN='$isbn' WHERE bookId=$bookId";
            $result = $this->conn->query($sql);
            // Determine if the update was successful
            if ($result) {
                return json_encode(array('type' => 'success', 'message' => 'Book successfully updated.'));
            } else {
                return json_encode(array('type' => 'fail', 'message' => 'Unable to update book details.'));
            }
        } else {
            // Insert new book record
            $sql = "INSERT INTO book (bookCategory, Title, Author, columnNumber, Accession, bookEdition, bookYear, Property, ISBN) VALUES ('$bookCategory', '$Title', '$Author', '$columnNumber', '$Accession', '$bookEdition', '$bookYear', '$Property', '$isbn')";
            $result = $this->conn->query($sql);
            // Determine if the insertion was successful
            if ($result) {
                return json_encode(array('type' => 'success', 'message' => 'Book successfully added.'));
            } else {
                return json_encode(array('type' => 'fail', 'message' => 'Unable to add book details.'));
            }
        }
    }

    // Method to retrieve all books
    public function getAllBooks() {
        $sql = "SELECT * FROM book";
        $result = $this->conn->query($sql);
        $books = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $books[] = $row;
            }
        }
        return $books;
    }

    // Method to delete a book
    public function deleteBook($deleteId) {
        $sql = "DELETE FROM book WHERE bookId = $deleteId";
        $result = $this->conn->query($sql);

        if ($result) {
            return json_encode(array('type' => 'success', 'message' => 'Book deleted successfully.'));
        } else {
            return json_encode(array('type' => 'fail', 'message' => 'Unable to delete book.'));
        }
    }

    // Method to get book details by ID
    public function getBookById($bookId) {
        $sql = "SELECT * FROM book WHERE bookId = $bookId";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }
}

$book = new Book();

// Save book details
if (isset($_POST['Title'])) {
    $saveBook = $book->saveBook($_POST);
    echo $saveBook;
}

// Delete book
if (isset($_POST['deleteId'])) {
    $deleteBook = $book->deleteBook($_POST['deleteId']);
    echo $deleteBook;
}

// Fetch book details by ID
if (isset($_POST['getBookById'])) {
    $bookId = $_POST['getBookById'];
    $bookDetails = $book->getBookById($bookId);
    echo json_encode($bookDetails);
}
?>
