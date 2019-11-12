<?php
namespace App\Http\Controllers;

use App\Repositories\ProductRepository;
use App\Repositories\UserRepository;
use App\Repositories\WishListRepository;
use Dotenv\Exception\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class ProductController
 * @package App\Http\Controllers
 */
class ProductController extends Controller
{
    private $repository;
    private $user;
    private $wishList;

    /**
     * ProductController constructor.
     * @param UserRepository $userRepository
     * @param WishListRepository $wishListrepository
     * @param ProductRepository $repository
     * @param Request $request
     */
    public function __construct(
        UserRepository $userRepository,
        WishListRepository $wishListrepository,
        ProductRepository $repository,
        Request $request
    )
    {
        $userId = $request->header('User-Id');
        $wishListId = $request->header('Wish-Id');
        $this->user = $userRepository->find($userId);
        $this->wishList = $wishListrepository->find($wishListId);
        $this->repository = $repository;
    }

    /**
     * @return JsonResponse
     */
    public function index()
    {
        $products = $this->wishList->products;
        return response()->json(['data' => $products], 200);
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
            $product = $this->repository->create([
                'wish_list_id' => $this->wishList->id,
                'name' => $request->get('name'),
                'link' => $request->get('link')
            ]);
            return response()->json(['data' => "The wish product with id {$product->id} has been created"], 201);
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
    public function show($id){
        try {
            $product = $this->repository->find($id);
            return response()->json(['data' => [$product]],200);
        } catch (ModelNotFoundException $modelNotFoundException) {
            return response()->json(['message' => "The product with {$id} doesn't exist"], 404);
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
            $product = $this->repository->update(
                $request->all(),
                $id
            );

            return response()->json(
                ['data' => "The product with id {$product->id} has been updated"],
                200
            );
        } catch (ModelNotFoundException $modelNotFoundException) {
            return response()->json(
                ['message' => "The product with {$id} doesn't exist"],
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
                ['data' => "The product with id {$id} has been deleted"],
                200
            );
        } catch (ModelNotFoundException $modelNotFoundException) {
            return response()->json(['message' => "The  product with {$id} doesn't exist"], 404);
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
            'link' => 'required|min:6',
        ];
        $this->validate($request, $rules);
    }
}
