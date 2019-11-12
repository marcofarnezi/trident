<?php
namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Dotenv\Exception\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    private $repository;

    /**
     * UserController constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return JsonResponse
     */
    public function index()
    {
        $users = $this->repository->all();
        return response()->json(['data' => $users], 200);
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
            $user = $this->repository->create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password'))
            ]);
            return response()->json(['data' => "The user with id {$user->id} has been created"], 201);
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
            $user = $this->repository->find($id);
            $user->wishLists;
            return response()->json(['data' => $user], 200);
        } catch (ModelNotFoundException $modelNotFoundException) {
            return response()->json(['message' => "The user with {$id} doesn't exist"], 404);
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
            $user = $this->repository->update(
                $request->all(),
                $id
            );

            return response()->json(
                ['data' => "The user with id {$user->id} has been updated"],
                200
            );
        } catch (ModelNotFoundException $modelNotFoundException) {
            return response()->json(
                ['message' => "The user with {$id} doesn't exist"],
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
                ['data' => "The user with id {$id} has been deleted"],
                200
            );
        } catch (ModelNotFoundException $modelNotFoundException) {
            return response()->json(['message' => "The user with {$id} doesn't exist"], 404);
        }
    }

    /**
     * @param Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    private function validateRequest(Request $request)
    {
        $rules = [
            'name' => 'required|min:6',
            'email' => 'required|email',
            'password' => 'required|min:6'
        ];
        $this->validate($request, $rules);
    }
}
