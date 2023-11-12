<?php

namespace App\Controllers;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use CodeIgniter\HTTP\Response;
use CodeIgniter\Database\RawSql;
use CodeIgniter\RESTful\ResourceController;

class TicketController extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
     
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $ticket = new \App\Models\Ticket();
        $data = $ticket->find($id);
        return $this->response->setStatusCode(Response::HTTP_OK)->setJSON($data);
    }


    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        // get auth to validate user role
        $key = getenv('TOKEN_SECRET');
        $header = $this->request->getServer('HTTP_AUTHORIZATION');
        $token = explode(' ', $header)[1];
        $decoded = JWT::decode($token, new Key($key, 'HS256'));

        $ticket = new \App\Models\Ticket();
        $data = $this->request->getJSON();
        $data->user_id = $decoded->user_id;
        $data->status = "PENDING";
        if (!$ticket->validate($data)) {
            $response = array(
                'status' => 'error',
                'error' => true,
                'messages' => $ticket->errors()
            );

            return $this->response->setStatusCode(Response::HTTP_BAD_REQUEST)->setJSON($response);
        }

        if($ticket->insert($data)){
            $response = array(
                'status' => 'success',
                'error' => false,
                'messages' => 'Ticket added successfully'
            );
    
            return $this->response->setStatusCode(Response::HTTP_CREATED)->setJSON($response);
        }else{
            $response = array(
                'status' => 'error',
                'error' => true,
                'messages' => $ticket->errors()
            );
    
            return $this->response->setStatusCode(Response::HTTP_OK)->setJSON($response);
        }

    }

    public function list()
    {
        // get auth to validate user role
        $key = getenv('TOKEN_SECRET');
        $header = $this->request->getServer('HTTP_AUTHORIZATION');
        $token = explode(' ', $header)[1];
        $decoded = JWT::decode($token, new Key($key, 'HS256'));

        $postData = $this->request->getPost();
        $response = array();
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $searchValue = $postData['search']['value'];
        $sortby = $postData['order'][0]['column']; // Column index
        $sortdir = $postData['order'][0]['dir']; // asc or desc
        $sortcolumn = $postData['columns'][$sortby]['data']; // Column name

        $ticket = new \App\Models\Ticket();

        if($decoded->role == "ADMIN"){
            $totalRecords = $ticket->select('ticket_id')->countAllResults();

            // get total of filtered search
            $totalRecordwithFilter = $ticket->select('ticket_id')
                ->orLike('first_name', $searchValue)
                ->orLike('last_name', $searchValue)
                ->orLike('email', $searchValue)
                ->orLike('description', $searchValue)
                ->orLike('status', $searchValue)
                ->orderBy($sortcolumn, $sortdir)
                ->countAllResults();
                

            // get all total
            // $records = $ticket->select('*')
            //     ->orLike('ticket_id', $searchValue)
            //     ->orLike('first_name', $searchValue)
            //     ->orLike('last_name', $searchValue)
            //     ->orLike('email', $searchValue)
            //     ->orLike('description', $searchValue)
            //     ->orLike('status', $searchValue)
            //     ->orderBy($sortcolumn, $sortdir)
            //     ->findAll($rowperpage, $start);

            // native innerjoin query
            $db      = \Config\Database::connect();
            $query = $db->query("SELECT *,categories.severity,office.office_name FROM ticket INNER JOIN categories ON ticket.category_id=categories.category_id INNER JOIN office on ticket.office_id=office.office_id WHERE ticket.ticket_id LIKE '$searchValue%%' OR ticket.first_name LIKE '$searchValue%%' OR ticket.last_name LIKE '$searchValue%%' OR ticket.email LIKE '$searchValue%%' OR ticket.status LIKE '$searchValue%%' OR categories.severity LIKE '$searchValue%%' ORDER BY $sortcolumn $sortdir LIMIT $start, $rowperpage");
            $records = $query->getResult('array');
            $data = array();

            foreach ($records as $record) {
                $data[] = array(
                    "ticket_id" => $record['ticket_id'],
                    "first_name" => $record['first_name'],
                    "last_name" => $record['last_name'],
                    "email" => $record['email'],
                    "description" => $record['description'],
                    "status" => $record['status'],
                    "office_name" => $record['office_name'],
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
        }else{
            $totalRecords = $ticket->select('ticket_id')
            ->where('user_id',$decoded->user_id)
            ->countAllResults();
            // get total of filtered search
            $totalRecordwithFilter = $ticket->select('ticket_id')
                ->orLike('first_name', $searchValue)
                ->orLike('last_name', $searchValue)
                ->orLike('email', $searchValue)
                ->orLike('description', $searchValue)
                ->orLike('status', $searchValue)
                ->where('user_id',$decoded->user_id)
                ->orderBy($sortcolumn, $sortdir)
                ->countAllResults();
                

            // get all total
            // $records = $ticket->select('*')
            //     ->orLike('ticket_id', $searchValue)
            //     ->orLike('first_name', $searchValue)
            //     ->orLike('last_name', $searchValue)
            //     ->orLike('email', $searchValue)
            //     ->orLike('description', $searchValue)
            //     ->orLike('status', $searchValue)
            //     ->orderBy($sortcolumn, $sortdir)
            //     ->findAll($rowperpage, $start);

            // native innerjoin query
            $db      = \Config\Database::connect();
            $query = $db->query("SELECT *,categories.severity,office.office_name FROM ticket INNER JOIN categories ON ticket.category_id=categories.category_id INNER JOIN office on ticket.office_id=office.office_id  INNER JOIN user_account ON ticket.user_id=user_account.user_id WHERE (ticket.ticket_id LIKE '$searchValue%%' OR ticket.first_name LIKE '$searchValue%%' OR ticket.last_name LIKE '$searchValue%%' OR ticket.email LIKE '$searchValue%%' OR ticket.status LIKE '$searchValue%%' OR categories.severity LIKE '$searchValue%%') AND ticket.user_id='$decoded->user_id' ORDER BY $sortcolumn $sortdir LIMIT $start, $rowperpage");
            $records = $query->getResult('array');
            $data = array();

            foreach ($records as $record) {
                $data[] = array(
                    "ticket_id" => $record['ticket_id'],
                    "first_name" => $record['first_name'],
                    "last_name" => $record['last_name'],
                    "email" => $record['email'],
                    "description" => $record['description'],
                    "status" => $record['status'],
                    "office_name" => $record['office_name'],
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
        
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $ticket = new \App\Models\Ticket();
        $data = $this->request->getJSON();
        unset($data->id);

        if (!$ticket->validate($data)) {
            $response = array(
                'status' => 'error',
                'error' => true,
                'messages' => $ticket->errors()
            );

            return $this->response->setStatusCode(Response::HTTP_NOT_MODIFIED)->setJSON($response);
        }

        if($ticket->update($id, $data)){
            $response = array(
                'status' => 'success',
                'error' => false,
                'messages' => 'Ticket updated successfully'
            );
    
            return $this->response->setStatusCode(Response::HTTP_OK)->setJSON($response);
        }else{
            $response = array(
                'status' => 'error',
                'error' => true,
                'messages' => $ticket->errors()
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
        //
    }
}
