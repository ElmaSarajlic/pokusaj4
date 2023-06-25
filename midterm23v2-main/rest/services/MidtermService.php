<?php
require_once __DIR__ . "/../dao/MidtermDao.php";

class MidtermService
{
    protected $dao;

    public function __construct()
    {
        $this->dao = new MidtermDao();
    }

    /** TODO
     * Implement service method to return detailed cap-table
     */
    public function cap_table()
    {
        return $this->dao->cap_table();
    }

    /** TODO
     * Implement service method to add cap table record
     */
    public function add_cap_table_record($id, $share_class_id, $share_class_category_id, $investor_id, $diluted_shares)
    {
        return $this->dao->add_cap_table_record($id, $share_class_id, $share_class_category_id, $investor_id, $diluted_shares);
    }

    /** TODO
     * Implement service method to return list of categories with total shares amount
     */
    public function categories()
    {
        return $this->dao->categories();
    }

    /** TODO
     * Implement service method to delete investor
     */
    public function delete_investor($id)
    {
        return $this->dao->delete_investor($id);
    }
}