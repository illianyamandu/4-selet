<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Utils\RetornoApi;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\Return_;

class UserController extends Controller
{
    public function list($user_id = null)
    {
        try {
            $user = $user_id == null ? User::all() : User::findOrFail($user_id);

            return response()->json($user);
        } catch (Exception $e) {
            return RetornoApi::GetRetornoErro('Ocorreu um erro');
        }
    }

    public function create(Request $request)
    {
        try {
            $errors = $this->validatorDataUser($request);
            if ($errors)
                return RetornoApi::GetRetornoErro($errors);

            DB::beginTransaction();

            $data = [
                'nome' => $request->nome,
                'telefone' => $request->telefone,
                'email' => $request->email,
                'cpf' => $request->cpf
            ];
            User::create($data);

            DB::commit();

            return RetornoApi::GetRetornoSucesso('Usuáiro cadastrado com sucesso');
        } catch (Exception $e) {
            return RetornoApi::GetRetornoErro($e->getMessage());
        }
    }

    public function edit(Request $request, $user_id)
    {
        try {
            $errors = $this->validatorDataUser($request);
            if ($errors)
                return RetornoApi::GetRetornoErro($errors);

            $data = [
                'nome' => $request->nome,
                'telefone' => $request->telefone,
                'email' => $request->email,
                'cpf' => $request->cpf
            ];

            $user = User::findOrFail($user_id);
            $user->update($data);

            return RetornoApi::GetRetornoSucesso('Dados alterados com sucesso');
        } catch (Exception $e) {
            return RetornoApi::GetRetornoErro($e->getMessage());
        }
    }

    public function delete($user_id)
    {
        try {
            User::findOrFail($user_id)->delete();
            return RetornoApi::GetRetornoSucesso('Usuário removido com sucesso');
        } catch (Exception $e) {
            return RetornoApi::GetRetornoErro('Ocorreu um erro');
        }
    }

    public function validatorDataUser($request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required',
            'telefone' => 'required|numeric',
            'email' => 'required|email',
            'cpf' => 'required',
        ], [
            'nome.required' => 'O nome é obrigatório',
            'telefone.required' => 'O telefone é obrigatório',
            'email.required' => 'O email é obrigatório',
            'cpf.required' => 'O cpf é obrigatório',
            'email.email' => 'Digite um email válido',
            'telefone.numeric' => 'Digite um telefone válido',
        ]);

        if ($validator->fails())
            return $validator->errors();

        if (strlen($request->telefone) != 10)
            return 'O telefone precisa estar na seguinte máscara: (XX)xxxxxxxx';

        if (strlen($request->cpf) != 11)
            return 'Digite um CPF válido!';

        return null;
    }
}
