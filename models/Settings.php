<?php

class Settings
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * Get system settings
     * 
     * @return array Settings data
     */
    public function get(): array
    {
        $query = "SELECT * FROM settings ORDER BY id LIMIT 1";
        $statement = $this->db->query($query);
        $result = $statement->fetch();
        
        return $result ?: [];
    }

    /**
     * Update system settings
     * 
     * @param array $data Settings data
     * @return bool Success status
     */
    public function update(array $data): bool
    {
        $query = "UPDATE settings SET 
                  company_name = :company_name,
                  address = :address,
                  contact_number = :contact_number,
                  tel_number = :tel_number,
                  email = :email,
                  fb_page = :fb_page,
                  delivery_days = :delivery_days,
                  price_validity_days = :price_validity_days,
                  printed_name = :printed_name
                  WHERE id = :id";
        
        $statement = $this->db->query($query, [
            'company_name' => $data['company_name'],
            'address' => $data['address'],
            'contact_number' => $data['contact_number'],
            'tel_number' => $data['tel_number'] ?? null,
            'email' => $data['email'],
            'fb_page' => $data['fb_page'] ?? null,
            'delivery_days' => $data['delivery_days'],
            'price_validity_days' => $data['price_validity_days'],
            'printed_name' => $data['printed_name'],
            'id' => $data['id']
        ]);
        
        return $statement->rowCount() > 0;
    }
}