<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use CodeIgniter\HTTP\Response;
use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        // get auth to validate user role
        $key = getenv('TOKEN_SECRET');
        $header = $this->request->getServer('HTTP_AUTHORIZATION');
        $token = explode(' ', $header)[1];
        $decoded = JWT::decode($token, new Key($key, 'HS256'));
        $ticket = new \App\Models\Ticket();
        $user = new \App\Models\User();

        if($decoded->role == "ADMIN"){
            // all ticket
            $totalTicket = $ticket->select('ticket_id')
            ->countAllResults();
            // pending ticket
            $totalTicketPending = $ticket->select('ticket_id')
            ->where("status", "PENDING")
            ->countAllResults();
            // processing ticket
            $totalTicketProcessing = $ticket->select('ticket_id')
            ->where("status", "PROCESSING")
            ->countAllResults();
            // resolved ticket
            $totalTicketResolved = $ticket->select('ticket_id')
            ->where("status", "RESOLVED")
            ->countAllResults();
            // total users
            $totalUsers = $user->select('user_id')
            ->countAllResults();

            $response = array(
                "totalTicket" => $totalTicket,
                "totalTicketPending" => $totalTicketPending,
                "totalTicketProcessing" => $totalTicketProcessing,
                "totalTicketResolved" => $totalTicketResolved,
                "totalUsers" => $totalUsers,
                "role" => $decoded->role
            );
            return $this->response->setJson($response);
        }else{
            // all ticket
            $totalTicket = $ticket->select('ticket_id')
            ->where("user_id",$decoded->user_id)
            ->countAllResults();
            $response = array(
                "totalTicket" => $totalTicket,
                "role" => $decoded->role
            );
            return $this->response->setJson($response);
        }
    }
}
