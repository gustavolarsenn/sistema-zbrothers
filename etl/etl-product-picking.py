import requests
import datetime, time, logging, os, traceback
import pandas as pd
from numpy import nan
from functions import functions
import json

from urllib.parse import quote_plus
from sqlalchemy import create_engine
engine = create_engine('postgresql://zbrothers:%s@localhost:5434/zbrothers' % quote_plus('zbrothers@2026'))

API_TOKEN = 'a841ba4682810a16845a10c47e790304f55f3fc5'

today = datetime.date.today()
today_str = today.strftime('%d/%m/%Y')

situacao_dict = {
    'aguardando_separacao': 1,
    'em_separacao': 4,
    'separada': 2,
    'embalada': 3
}

def getAllProductsPicking(situacao_dict: dict, today_str: str) -> pd.DataFrame:
    df_list = []
    for situacao, situacao_id in situacao_dict.items():
        print(f'Getting data for situacao: {situacao} (id: {situacao_id})')
        df = functions.getInfosPesquisar(
            url='https://api.tiny.com.br/api2/separacao.pesquisa.php',
            params={
                'token': API_TOKEN,
                'dataInicial': today_str,
                'situacao': situacao_id
            }
        )
        df['situacao'] = situacao
        df_list.append(df)
    return pd.concat(df_list, ignore_index=True)

df_all_products_picking = getAllProductsPicking(situacao_dict, today_str)

df_all_products_picking_detail = functions.getInfosObter('https://api.tiny.com.br/api2/separacao.obter.php', 
                                                         {'token': API_TOKEN, 'formato': 'json', 'idSeparacao': None},
                                                         df_all_products_picking['id'].tolist(),
                                                         'idSeparacao')

df_all_products_picking_merged = df_all_products_picking_detail.merge(df_all_products_picking, on='id', how='left', validate='1:1')#.drop(columns=['id_y']).rename(columns={'id_x': 'id'})

df_all_products_picking_columns = df_all_products_picking_merged[['id', 'idOrigem_x', 'objOrigem_x', 'situacao_x', 'situacaoCheckout',
       'dataCriacao_x', 'itens', 'qtdVolumes', 'numero_x', 'dataEmissao_x',
       'numeroPedidoEcommerce_x', 'idFormaEnvio_x', 'formaEnvio',
       'idContato_x', 'destinatario_x', 'situacaoOrigem', 'dataSeparacao_x',
       'idUsuarioEmbalador', 'dataCheckout_x', 'idOrigemVinc',
       'objOrigemVinc', 'situacaoVenda']]

df_all_products_picking_rename = df_all_products_picking_columns.rename(columns={x: x.replace('_x', '') for x in df_all_products_picking_columns.columns})\
    .rename(columns={'idUsuarioEmbalador': 'picking_operator_id'})

df_all_products_picking_rename["itens"] = df_all_products_picking_rename["itens"].apply(
    lambda x: json.dumps(x, ensure_ascii=False) if x is not None else None
)

df_all_products_picking_rename[['dataCriacao', 'dataEmissao', 'dataSeparacao', 'dataCheckout']] = df_all_products_picking_rename[['dataCriacao', 'dataEmissao', 'dataSeparacao', 'dataCheckout']].apply(lambda x: pd.to_datetime(x, format='%d/%m/%Y'))

df_all_products_picking_rename.to_parquet(f'etl/data/product_picking-{datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')}.parquet', index=False)