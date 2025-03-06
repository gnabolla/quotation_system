<?php

class QuotationItem
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * Create a new quotation item
     * 
     * @param array $data Quotation item data
     * @return int The ID of the newly created quotation item
     */
    public function create(array $data): int
    {
        $query = "INSERT INTO quotation_items 
                  (quotation_id, item_no, quantity, unit, description, supplier_price, 
                   markup_percentage, final_price, total_amount) 
                  VALUES 
                  (:quotation_id, :item_no, :quantity, :unit, :description, :supplier_price, 
                   :markup_percentage, :final_price, :total_amount)";
        
        $this->db->query($query, [
            'quotation_id' => $data['quotation_id'],
            'item_no' => $data['item_no'],
            'quantity' => $data['quantity'],
            'unit' => $data['unit'],
            'description' => $data['description'],
            'supplier_price' => $data['supplier_price'],
            'markup_percentage' => $data['markup_percentage'],
            'final_price' => $data['final_price'],
            'total_amount' => $data['total_amount']
        ]);
        
        return (int) $this->db->connection->lastInsertId();
    }

    /**
     * Get items for a quotation
     * 
     * @param int $quotationId Quotation ID
     * @return array Array of quotation items
     */
    public function findByQuotationId(int $quotationId): array
    {
        $query = "SELECT * FROM quotation_items WHERE quotation_id = :quotation_id ORDER BY item_no";
        $statement = $this->db->query($query, ['quotation_id' => $quotationId]);
        return $statement->fetchAll();
    }

    /**
     * Calculate total profit for a quotation
     * 
     * @param int $quotationId Quotation ID
     * @return float Total profit
     */
    public function calculateTotalProfit(int $quotationId): float
    {
        $query = "SELECT SUM((final_price - supplier_price) * quantity) as total_profit 
                  FROM quotation_items 
                  WHERE quotation_id = :quotation_id";
        
        $statement = $this->db->query($query, ['quotation_id' => $quotationId]);
        $result = $statement->fetch();
        
        return (float)($result['total_profit'] ?? 0);
    }
}