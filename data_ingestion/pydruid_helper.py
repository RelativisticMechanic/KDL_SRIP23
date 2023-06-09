import pandas as pd
import requests
import settings

druid_url = f"{settings.DRUID_PROTOCOL}://{settings.DRUID_SERVERLOC}:{settings.DRUID_PORT}{settings.DRUID_SQL_PATH}"

def ExecuteSQLQuery(query):
    json_data = requests.post(druid_url, json={"query": query}).json()
    df = pd.DataFrame.from_dict(pd.json_normalize(json_data), orient='columns')
    return df

def ExecuteSQLQueryGetJSON(query):
    json_data = requests.post(druid_url, json={"query": query}).json()
    return json_data

def ExecuteSQLQueryGetCSV(query):
    csv = requests.post(druid_url, json={"query": query, "resultFormat": "csv", "header": True}).text
    return csv