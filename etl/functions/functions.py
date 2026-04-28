import requests
import datetime, time, logging, os, traceback
import pandas as pd

def getInfosPesquisar(url: str, params: dict) -> pd.DataFrame:
    """
    Busca informações que são paginadas
    """
    res = requests.get(url, params=params)
    print(res.json())
    if res.json()['retorno']['status'] != 'OK':
        print(f"Falha ao obter quantidade de páginas: {res.json()['retorno']['status']}, possivelmente por sobrecarga na API.\nAguardando 90 segundos para tentar novamente...")
        time.sleep(90) 
        
        res = requests.get(url, params=params)
        
    try:
        # Código 32 indica que não há dados para a consulta, então retornamos um DataFrame vazio
        if (res.json()['retorno']['codigo_erro'] == 32):
            return pd.DataFrame()  # Retorna DataFrame vazio se não houver dados para a consulta
    except:
        pass
    
    print(res.json())
    num_pages = res.json()['retorno']['numero_paginas']
    print(f"Quantidade de páginas: {num_pages}")
    
    for i in range(1, num_pages + 1):
        print(f"Obtendo página {i} de {num_pages}...")
        params['pagina'] = i
        res = requests.get(url, params=params)
        
        if res.json()['retorno']['status'] != 'OK':
            print(f"Falha ao obter página {i}: {res.json()['retorno']['status']}, possivelmente por sobrecarga na API.\nAguardando 90 segundos para tentar novamente...")
            time.sleep(90) 
            
            res = requests.get(url, params=params)
        
        df = pd.DataFrame(res.json()['retorno']['separacoes'])
        
        if i == 1:
            df_final = df
        else:
            df_final = pd.concat([df_final, df], ignore_index=True)

    return df_final


def getInfosObter(url: str, params: dict, ids_list: list, id_name: str) -> pd.DataFrame:
    """
    Obtém detalhes de um item específico, não paginado
    """
    df_final = pd.DataFrame()
    for id in ids_list:
        params[id_name] = id
        res = requests.get(url, params=params)
        print(res.json())
        if res.json()['retorno']['status'] != 'OK':
            print(f"Falha ao obter detalhes do item {id}: {res.json()['retorno']['status']}, possivelmente por sobrecarga na API.\nAguardando 90 segundos para tentar novamente...")
            time.sleep(90) 
        
            res = requests.get(url, params=params)
        
        df_final = pd.concat([df_final, pd.DataFrame([res.json()['retorno']['separacao']])], ignore_index=True)

    return df_final