<?php

namespace App\Controllers;

use CodeIgniter\HTTP\Response;
use CodeIgniter\RESTful\ResourceController;

class OfficeController extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        //
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $post = new \App\Models\Office();
        $data = $post->find($id);
        return $this->response->setStatusCode(Response::HTTP_OK)->setJSON($data);
    }

    public function showOffices()
    {
        $office = new \App\Models\Office();
        $data = $office->findAll();
        $jsonData = array();
        foreach ($data as $record) {
            $jsonData[] = array(
                "office_id" => $record['office_id'],
                "office_name" => $record['office_name'],
            );
        }

        $response = array(
            "data" => $jsonData
        );

        return $this->response->setJson($response);
    }

    public function list()
    {
        $postData = $this->request->getPost();

        $response = array();
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $searchValue = $postData['search']['value'];
        $sortby = $postData['order'][0]['column']; // Column index
        $sortdir = $postData['order'][0]['dir']; // asc or desc
        $sortcolumn = $postData['columns'][$sortby]['data']; // Column name

        $office = new \App\Models\Office();
        $totalRecords = $office->select('office_id')->countAllResults();

        // get total of filtered search
        $totalRecordwithFilter = $office->select('office_id')
            ->orLike('office_name', $searchValue)
            ->orLike('office_code', $searchValue)
            ->orLike('divisionorsection_name', $searchValue)
            ->orLike('divisionorsection_code', $searchValue)
            ->orderBy($sortcolumn, $sortdir)
            ->countAllResults();
        // get all total
        $records = $office->select('*')
            ->orLike('office_name', $searchValue)
            ->orLike('office_code', $searchValue)
            ->orLike('divisionorsection_name', $searchValue)
            ->orLike('divisionorsection_code', $searchValue)
            ->orderBy($sortcolumn, $sortdir)
            ->findAll($rowperpage, $start);


        $data = array();

        foreach ($records as $record) {
            $data[] = array(
                "office_id" => $record['office_id'],
                "office_name" => $record['office_name'],
                "office_code" => $record['office_code'],
                "divisionorsection_name" => $record['divisionorsection_name'],
                "divisionorsection_code" => $record['divisionorsection_code'],
                "created_at" => $record['created_at'],
                "updated_at" => $record['updated_at']
            );
        }


        $response = array(
            "draw" => intval($draw),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecordwithFilter,
            "data" => $data
        );

        return $this->response->setJson($response);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $office = new \App\Models\Office();
        $data = $this->request->getJSON();

        if (!$office->validate($data)) {
            $response = array(
                'status' => 'error',
                'error' => true,
                'messages' => $office->errors()
            );

            return $this->response->setStatusCode(Response::HTTP_OK)->setJSON($response);
        }

        if($office->insert($data)){
            $response = array(
                'status' => 'success',
                'error' => false,
                'messages' => 'Office added successfully'
            );
            return $this->response->setStatusCode(Response::HTTP_CREATED)->setJSON($response);
        }else{
            $response = array(
                'status' => 'error',
                'error' => true,
                'messages' => $office->errors()
            );
            return $this->response->setStatusCode(Response::HTTP_BAD_REQUEST)->setJSON($response);
        }
    }


    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $office = new \App\Models\Office();
        $data = $this->request->getJSON();
        unset($data->id);


        if (!$office->validate($data)) {
            $response = array(
                'status' => 'error',
                'error' => true,
                'messages' => $office->errors()
            );

            return $this->response->setStatusCode(Response::HTTP_NOT_MODIFIED)->setJSON($response);
        }

        if($office->update($id, $data)){
            $response = array(
                'status' => 'success',
                'error' => false,
                'messages' => 'office updated successfully'
            );
    
            return $this->response->setStatusCode(Response::HTTP_OK)->setJSON($response);
        }else{
            $response = array(
                'status' => 'error',
                'error' => true,
                'messages' => $office->errors()
            );
    
            return $this->response->setStatusCode(Response::HTTP_BAD_REQUEST)->setJSON($response);
        }
        
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        $office = new \App\Models\Office();

        if ($office->delete($id)) {
            $response = array(
                'status' => 'success',
                'error' => false,
                'messages' => 'Office deleted successfully'
            );

            return $this->response->setStatusCode(Response::HTTP_OK)->setJSON($response);
        }

        $response = array(
            'status' => 'error',
            'error' => true,
            'messages' => 'Office not found'
        );

        return $this->response->setStatusCode(Response::HTTP_NOT_FOUND)->setJSON($response);
    }
}
