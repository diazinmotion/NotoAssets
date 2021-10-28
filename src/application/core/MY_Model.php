<?php

defined('BASEPATH') or exit('No direct script access allowed');

class MY_Model extends CI_Model
{
    protected $tabel        = '';
    protected $max_batch    = 100;
    protected $seq_name     = '';

    function __construct()
    {
        parent::__construct();
    }

    function get($tabel = null, $where = [], $join_args = [], $join_type = 'left', $order_by = [], $limit = null, $offset = null, $like = [], $select = null, $group_by = [], $plain_where = null)
    {
        if ($tabel == null) {
            $tabel = $this->tabel;
        }

        // bila ada klausa select
        if($select){
            $this->db->select($select);
        }

        // bila ada klausa where
        if($where){
            $this->db->where($where);
        }

        if($plain_where){
            $this->db->where($plain_where, NULL, false);
        }

        // group by
        if($group_by){
            $this->db->group_by($group_by);
        }

        // bila ada klausa order by [nama_kolom, ASC/DESC]
        if ($order_by) {
            foreach($order_by as $i => $v){
                $this->db->order_by($i, $v);
            }
        }

        // bila get berupa join statement
        if ($join_args) {
            foreach ($join_args as $key => $row) {
                $this->db->join($key, $row, $join_type);
            }
        }

        // bila ada limit & offeset
        if ($limit || $offset) {
            $offset = ($offset) ? $offset : 0;
            $this->db->limit($limit, $offset);
        }

        // bila ada like clause
        if(is_array($like) && $like){
            $is_first = true;

            // bila ada where, maka pake grouping
            if($where){ $this->db->group_start(); }

            foreach($like as $i => $v){
                if($is_first){
                    $this->db->like($i, $v);
                    $is_first = false;
                    
                    continue;
                }

                $this->db->or_like($i, $v);
            }

            // bila ada where, maka pake grouping
            if($where){ $this->db->group_end(); }
        }
        // end like clause

        $query = $this->db->get($tabel);
        
        return ($query) ? $query->result() : null;
    }

    function get_count($tabel = null, $where = [], $join_args = [], $join_type = 'left', $order_by = [], $limit = null, $offset = null, $like = [], $field_name = null, $group_by = [], $plain_where = null)
    {
        if ($tabel == null) {
            $tabel = $this->tabel;
        }

        // bila ada klausa select
        if($field_name){
            $this->db->select('COUNT('.$field_name.') AS total');
        }

        // bila ada klausa where
        if($where){
            $this->db->where($where);
        }

        if($plain_where){
            $this->db->where($plain_where, NULL, false);
        }

        // group by
        if($group_by){
            $this->db->group_by($group_by);
        }

        // bila ada klausa order by [nama_kolom, ASC/DESC]
        if ($order_by) {
            foreach($order_by as $i => $v){
                $this->db->order_by($i, $v);
            }
        }

        // bila get berupa join statement
        if ($join_args) {
            foreach ($join_args as $key => $row) {
                $this->db->join($key, $row, $join_type);
            }
        }

        // bila ada limit & offeset
        if ($limit || $offset) {
            $offset = ($offset) ? $offset : 0;
            $this->db->limit($limit, $offset);
        }

        // bila ada like clause
        if(is_array($like) && $like){
            $is_first = true;

            // bila ada where, maka pake grouping
            if($where){ $this->db->group_start(); }

            foreach($like as $i => $v){
                if($is_first){
                    $this->db->like($i, $v);
                    $is_first = false;
                    
                    continue;
                }

                $this->db->or_like($i, $v);
            }

            // bila ada where, maka pake grouping
            if($where){ $this->db->group_end(); }
        }
        // end like clause

        $query = $this->db->get($tabel);
        
        return ($query) ? $query->row() : 0;
    }

    function insert($tabel = null, $data = [])
    {
        $output     = false;
        $is_batch   = false;

        if ($tabel == null) {
            $tabel = $this->tabel;
        }

        if (!$data) {
            return false;
        }

        // cek apakah data merupakan batch atau hanya single saja?
        if (!isset($data[0]) || !is_array($data[0])) {
            $query      = $this->db->insert($tabel, $data);
        } else {
            $query      = $this->db->insert_batch($tabel, $data, null, $this->max_batch);
            $is_batch   = true;
        }

        // bila berhasil diinsert
        if ($query) {
            // cek apakah ini menggunakan auto increment, bila ya maka return id insertnya
            if (!$is_batch) {
                $output = $this->_last_insert_id($this->seq_name);
            } else {
                $output = true;
            }
        }

        return $output;
    }

    function update($tabel = null, $where = [], $data = [])
    {
        if ($tabel == null) {
            $tabel = $this->tabel;
        }

        if (!$where) {
            return false;
        }
        $query = $this->db->update($tabel, $data, $where);

        return ($query) ? true : false;
    }

    function delete($tabel = null, $where = [])
    {
        if ($tabel == null) {
            $tabel = $this->tabel;
        }

        if (!$where) {
            return false;
        }

        $this->db->where($where);

        $query = $this->db->delete($tabel);

        return ($query) ? true : false;
    }

    function _last_insert_id($seq_name = null)
    {
        return $this->db->insert_id($seq_name);
    }

    function _last_query(){
        return $this->db->last_query();
    }
}

/* End of file MY_Model.php */
