<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class FileDatabase
{
    private $dataPath;

    public function __construct()
    {
        $this->dataPath = storage_path('app/database');
        if (!File::exists($this->dataPath)) {
            File::makeDirectory($this->dataPath, 0755, true);
        }
    }

    public function getUsers()
    {
        $file = $this->dataPath . '/users.json';
        if (!File::exists($file)) {
            return [];
        }
        return json_decode(File::get($file), true) ?: [];
    }

    public function saveUsers($users)
    {
        $file = $this->dataPath . '/users.json';
        File::put($file, json_encode($users, JSON_PRETTY_PRINT));
    }

    public function findUserByEmail($email)
    {
        $users = $this->getUsers();
        foreach ($users as $user) {
            if ($user['email'] === $email) {
                return $user;
            }
        }
        return null;
    }

    public function createUser($userData)
    {
        $users = $this->getUsers();
        
        // Check if user already exists
        if ($this->findUserByEmail($userData['email'])) {
            throw new \Exception('User with this email already exists');
        }

        // Add new user
        $userData['id'] = count($users) + 1;
        $userData['created_at'] = now()->toISOString();
        $userData['updated_at'] = now()->toISOString();
        $userData['email_verified_at'] = null;
        
        $users[] = $userData;
        $this->saveUsers($users);
        
        return $userData;
    }

    public function logAudit($eventType, $status, $data, $userId = null)
    {
        $file = $this->dataPath . '/audit_logs.json';
        $logs = File::exists($file) ? json_decode(File::get($file), true) ?: [] : [];
        
        $logEntry = [
            'id' => count($logs) + 1,
            'event_type' => $eventType,
            'status' => $status,
            'user_id' => $userId,
            'data' => $data,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now()->toISOString()
        ];
        
        $logs[] = $logEntry;
        File::put($file, json_encode($logs, JSON_PRETTY_PRINT));
    }
}