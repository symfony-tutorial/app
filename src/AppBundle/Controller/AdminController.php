<?php

namespace AppBundle\Controller;

use AccessBundle\UserService;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{

    public function indexAction() {
        return $this->render('AppBundle:Admin:index.html.twig');
    }

    public function listUsersAction() {

        return $this->render('AppBundle:Admin:users.html.twig');
    }

    public function findUsersAction(Request $request) {
        $username = $request->get('_search');
        $page = $request->get('page');
        $rows = $request->get('rows');

        if ($username === 'false') {
            $username = false;
        }
        /* @var $userServie UserService */
        $userService = $this->get(UserService::ID);
        $total = $userService->count();
        $totalPages = ceil($total / $rows);

        $responce = new stdClass();
        $responce->page = $page;
        $responce->total = $totalPages;
        $responce->records = $total;
        $users = $userService->find($username, ($page - 1) * $rows, $rows);
        foreach ($users as $user) {
            $row = array($user->getUsername(), $user->getRoles());
            $responce->rows[] = array('id' => $user->getId(), 'cell' => $row);
        }

        return new JsonResponse($responce);
    }

    public function editUserAction(Request $request) {
        /* @var $userServie UserService */
        $userService = $this->get(UserService::ID);

        $action = $request->get('oper');
        $username = $request->get('username');
        $password = $request->get('password');
        $roles = explode(',', str_replace(' ', '', $request->get('roles')));
        if ($action === 'add') {
            $userService->create($username, $password, $roles);
        }

        return new JsonResponse();
    }

}
