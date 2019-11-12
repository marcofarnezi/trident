<?php
namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\Repositories\WishListRepository;
use Dotenv\Exception\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class WishListController
 * @package App\Http\Controllers
 */
class WishListController extends Controller
{
    private $repository;
    private $user;

    /**
     * WishListController constructor.
     * @param UserRepository $userRepository
     * @param WishListRepository $repository
     * @param Request $request
     */
    public function __construct(UserRepository $userRepository, WishListRepository $repository, Request $request)
    {
        $userId = $request->header('User-Id');
        $this->user = $userRepository->find($userId);

        $this->repository = $repository;
    }

    /**
     * @return JsonResponse
     */
    public function index()
    {
        $wishLists = $this->user->wishLists;
        return response()->json(['data' => $wishLists], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        try {
            $this->validateRequest($request);
            $wishList = $this->repository->create([
                'user_id' => $this->user->id,
                'title' => $request->get('title')
            ]);
            return response()->json(['data' => "The wish list with id {$wishList->id} has been created"], 201);
        } catch (ValidationException $validationException) {
            return response()->json(
                ['message' => $validationException->getMessage()],
                400
            );
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        try {
            $wishLst = $this->repository->find($id);
            $wishLst->products;
            return response()->json(['data' => [$wishLst]],200);
        } catch (ModelNotFoundException $modelNotFoundException) {
            return response()->json(['message' => "The wish list with {$id} doesn't exist"], 404);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $wishList = $this->repository->update(
                $request->all(),
                $id
            );

            return response()->json(
                ['data' => "The wish list with id {$wishList->id} has been updated"],
                200
            );
        } catch (ModelNotFoundException $modelNotFoundException) {
            return response()->json(
                ['message' => "The wish list with {$id} doesn't exist"],
                404
            );
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try {
            $this->repository->delete($id);
            return response()->json(
                ['data' => "The  wish list with id {$id} has been deleted"],
                200
            );
        } catch (ModelNotFoundException $modelNotFoundException) {
            return response()->json(['message' => "The  wish list with {$id} doesn't exist"], 404);
        }
    }

    /**
     * @param Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    private function validateRequest(Request $request)
    {
        $rules = [
            'title' => 'required|min:6',
        ];
        $this->validate($request, $rules);
    }
}
