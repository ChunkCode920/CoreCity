<?php
set_include_path(getcwd() . '/../');  // Move up one directory to the root

// Now require the MemberModel.php file
require_once 'Model/MemberModel.php';


class MemberController
{
    private $model;
    
    public function __construct()
    {
        $this->model = new MemberModel();
    }

    public function listUsers() {
        return $this->model->getAllUsers();
    }

    public function listStaff() {
        return $this->model->getAllStaff();
    }

    public function listUserAppointments() {
        return $this->model->getUserAppointments();
    }

    public function getCreationDate($user_id) {
        return $this->model->creationDate($user_id);
    }

    public function getClosestUserAppointment($user_id) {
        return $this->model->getClosestAppointment($user_id);
    }

    public function getFullName($role) {
        return $this->model->getFullName($role);
    }

    public function userViewAppointment($id) {
        return $this->model->userAppointmentByID($id);
    }

    public function staffViewAppointment($id) {
        return $this->model->staffAppointmentByID($id);
    }

    public function cancelAppointment($id) {
        return $this->model->cancelAppointment($id);
    }
    
    public function deleteAppointment($id) {
        return $this->model->deleteAppointment($id);
    }

    public function totalReports() {
        return $this->model->totalReports();
    }

    public function viewReports() {
        return $this->model->viewReports();
    }

    public function viewIndividualReport($id) {
        return $this->model->viewIndividualReport($id);
    }

    public function deleteReport($id) {
        return $this->model->deleteReport($id);
    }

    public function viewUser($id) {
        return $this->model->viewUser($id);
    }

    public function addUser($fullname, $username, $email, $password, $pfp) {
        return $this->model->addUser($fullname, $username, $email, $password, $pfp);
    }

    public function deleteUser($id) {
        return $this->model->deleteUser($id);
    }

    public function numberUsers() {
        return $this->model->numberUsers();
    }

    public function numberStaff() {
        return $this->model->numberStaff();
    }

    public function viewStaff($id) {
        return $this->model->viewStaff($id);
    }

    public function deleteStaff($id) {
        return $this->model->deleteStaff($id);
    }

    public function addStaff($fullname, $username, $email, $password, $pfp) {
        return $this->model->addStaff($fullname, $username, $email, $password, $pfp);
    }

    public function modUser($user_id, $fullname, $username, $email, $password, $pfpPath) {
        return $this->model->modUser($user_id, $fullname, $username, $email, $password, $pfpPath);
    }

    public function modStaff($staff_id, $fullname, $username, $email, $password, $pfpPath) {
        return $this->model->modStaff($staff_id, $fullname, $username, $email, $password, $pfpPath); 
    }

    public function modAdmin($admin_id, $fullname, $username, $email, $password, $pfpPath) {
        return $this->model->modAdmin($admin_id, $fullname, $username, $email, $password, $pfpPath);
    }

    public function totalAppointments() {
        return $this->model->totalAppointments();
    }
}