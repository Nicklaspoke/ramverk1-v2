--
-- Creating a very small Book table.
--



--
-- Table Book
--
DROP TABLE IF EXISTS Book;
CREATE TABLE Book (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "title" TEXT NOT NULL,
    "author" TEXT NOT NULL,
    "number_of_pages" INTEGER NOT NULL,
    "genere" TEXT NOT NULL,
    "image_link" TEXT NOT NULL
);
