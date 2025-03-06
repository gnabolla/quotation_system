<?php

class Quotation
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * Create a new quotation
     * 
     * @param array $data Quotation data
     * @return int The ID of the newly created quotation
     */
    public function create(array $data): int
    {
        $query = "INSERT INTO quotations (quotation_no, date, department, purpose) 
                  VALUES (:quotation_no, :date, :department, :purpose)";
        
        $this->db->query($query, [
            'quotation_no' => $data['quotation_no'],
            'date' => $data['date'],
            'department' => $data['department'],
            'purpose' => $data['purpose']
        ]);
        
        return (int) $this->db->connection->lastInsertId();
    }

    /**
     * Get a quotation by ID
     * 
     * @param int $id Quotation ID
     * @return array|false Quotation data or false if not found
     */
    public function find(int $id)
    {
        $query = "SELECT * FROM quotations WHERE id = :id";
        $statement = $this->db->query($query, ['id' => $id]);
        return $statement->fetch();
    }

    /**
     * Get all quotations
     * 
     * @return array Array of quotations
     */
    public function all(): array
    {
        $query = "SELECT * FROM quotations ORDER BY created_at DESC";
        $statement = $this->db->query($query);
        return $statement->fetchAll();
    }

    /**
     * Generate a new quotation number
     * 
     * @return string Quotation number
     */
    public function generateQuotationNumber(): string
    {
        $query = "SELECT MAX(SUBSTRING_INDEX(quotation_no, '-', -1)) as last_num 
                  FROM quotations 
                  WHERE quotation_no LIKE :pattern";
        
        $year = date('Y');
        $month = date('m');
        $pattern = "QUO-{$year}{$month}-%";
        
        $statement = $this->db->query($query, ['pattern' => $pattern]);
        $result = $statement->fetch();
        
        $lastNum = $result['last_num'] ?? 0;
        $newNum = (int)$lastNum + 1;
        
        return "QUO-{$year}{$month}-" . str_pad($newNum, 3, '0', STR_PAD_LEFT);
    }
}