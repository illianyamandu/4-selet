<?php

namespace App\Utils;

class RetornoApi
{
  public static function GetRetornoErro($erros, $codigo = 400)
  {
    return response()->json(['error' => $erros], $codigo);
  }

  public static function GetRetornoSucesso($mensagem, $codigo = 200)
  {
    return response()->json(['mensagem' => $mensagem], $codigo);
  }

  public static function GetRetornoSucessoID($mensagem, $id, $codigo)
  {
    return response()->json(['mensagem' => $mensagem, 'id' => $id], $codigo);
  }
}
