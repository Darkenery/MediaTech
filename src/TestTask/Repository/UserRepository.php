<?php
/**
 * Created by PhpStorm.
 * User: darke_000
 * Date: 29.06.2016
 * Time: 21:33
 */

namespace TestTask\Repository;

use Doctrine\DBAL\Connection;
use TestTask\Entity\User;

class UserRepository
{
    protected $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function getAllUsers()
    {

        $userList = $this->getUserList();
        foreach ($userList as $userId) {
            $earned = null;
            $paid = null;

            $stmt = $this->db->executeQuery('SELECT users.hold, earned.date, earned.earned FROM users LEFT JOIN earned ON users.user_id = earned.user_id WHERE users.user_id =?', array($userId));
            $result = $stmt->fetchAll();
            $hold = $result[0]['hold'];
            foreach ($result as $row) {
                $earned[] = array('date' => $row['date'], 'earned' => $row['earned']);
            }

            $stmt = $this->db->executeQuery('SELECT paid_amount FROM paid WHERE user_id = ?', array($userId));
            $result = $stmt->fetchAll();
            foreach ($result as $row)
                $paid[] = $row['paid_amount'];

            $userData = array('userId' => $userId, 'hold' => $hold, 'earned' => $earned, 'paid' => $paid);

            $users[] = $this->userBuilder($userData);
        }

        return $users;
    }

    public function getUserList()
    {
        $stmt = $this->db->executeQuery('SELECT user_id FROM users');
        $users = $stmt->fetchAll();
        foreach ($users as $user)
            $userList[] = $user['user_id'];
        return $userList;
    }

    public function userBuilder(array $userData)
    {
        $user = new User;

        $user->setUserId($userData['userId']);
        $user->setHold($userData['hold']);
        $user->setEarned($userData['earned']);
        $user->setPaid($userData['paid']);

        return $user;
    }

}