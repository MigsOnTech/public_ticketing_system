<?php

namespace App\Controllers;

use CodeIgniter\HTTP\Response;
use CodeIgniter\RESTful\ResourceController;

class CategoriesController extends ResourceController
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

    public function showCategories()
    {
        $categories = new \App\Models\Categories();
        $data = $categories->findAll();
        $jsonData = array();
        foreach ($data as $record) {
            $jsonData[] = array(
                "category_id" => $record['category_id'],
                "severity" => $record['severity'],
            );
        }

        $response = array(
            "data" => $jsonData
        );

        return $this->response->setJson($response);
    }


    public function show($id = null)
    {
        $categories = new \App\Models\Categories();
        $data = $categories->find($id);
        return $this->response->setStatusCode(Response::HTTP_OK)->setJSON($data);
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

        $categories = new \App\Models\Categories();
        $totalRecords = $categories->select('category_id')->countAllResults();

        // get total of filtered search
        $totalRecordwithFilter = $categories->select('category_id')
            ->orLike('severity', $searchValue)
            ->orLike('created_at', $searchValue)
            ->orLike('updated_at', $searchValue)
            ->orderBy($sortcolumn, $sortdir)
            ->countAllResults();
        // get all total
        $records = $categories->select('*')
            ->orLike('severity', $searchValue)
            ->orLike('created_at', $searchValue)
            ->orLike('updated_at', $searchValue)
            ->orderBy($sortcolumn, $sortdir)
            ->findAll($rowperpage, $start);


        $data = array();

        foreach ($records as $record) {
            $data[] = array(
                "category_id" => $record['category_id'],
                "severity" => $record['severity'],
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
        $categories = new \App\Models\Categories();
        $data = $this->request->getJSON();

        if (!$categories->validate($data)) {
            $response = array(
                'status' => 'error',
                'error' => true,
                'messages' => $categories->errors()
            );

            return $this->response->setStatusCode(Response::HTTP_OK)->setJSON($response);
        }

        if($categories->insert($data)){
            $response = array(
                'status' => 'success',
                'error' => false,
                'messages' => 'Category added successfully'
            );
            return $this->response->setStatusCode(Response::HTTP_CREATED)->setJSON($response);
        }else{
            $response = array(
                'status' => 'error',
                'error' => true,
                'messages' => $categories->errors()
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
        $categories = new \App\Models\Categories();
        $data = $this->request->getJSON();
        unset($data->id);

        if (!$categories->validate($data)) {
            $response = array(
                'status' => 'error',
                'error' => true,
                'messages' => $categories->errors()
            );

            return $this->response->setStatusCode(Response::HTTP_NOT_MODIFIED)->setJSON($response);
        }

        if($categories->update($id, $data)){
            $response = array(
                'status' => 'success',
                'error' => false,
                'messages' => 'Category updated successfully'
            );
    
            return $this->response->setStatusCode(Response::HTTP_OK)->setJSON($response);
        }else{
            $response = array(
                'status' => 'error',
                'error' => true,
                'messages' => $categories->errors()
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
        $categories = new \App\Models\Categories();

        if ($categories->delete($id)) {
            $response = array(
                'status' => 'success',
                'error' => false,
                'messages' => 'Category deleted successfully'
            );

            return $this->response->setStatusCode(Response::HTTP_OK)->setJSON($response);
        }

        $response = array(
            'status' => 'error',
            'error' => true,
            'messages' => 'Category not found'
        );

        return $this->response->setStatusCode(Response::HTTP_NOT_FOUND)->setJSON($response);
    }
}