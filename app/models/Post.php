<?php
/*
 * Example model Not Part of framework
 */

class Post
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getPosts()
    {
        $this->db->query('SELECT * FROM posts');

        return $this->db->resultset();
    }

}