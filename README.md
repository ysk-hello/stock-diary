# 株日記
株の取引を記録する日記

## 環境
* Ubuntu: 22.04.2 LTS
* MySQL: 8.0.35
* PHP: 8.1.2
* Laravel: 10.12.0

## 環境構築
### データベース準備
#### 接続設定
データベースの接続設定を行う。
```
/.env
```
#### データベース作成
データベース、テーブルを作成する。
```
php artisan migrate
```

## 補足: 主なファイル
| 種類 | 場所 |
| - | - |
| Model | /app/Models |
| View | /resources/views |
| Controller | /app/Http/Controllers |
| Migration | /database/migrations |
| Tests | /tests/Feature |
| Routing | /routes/web.php |
| Docker | /docker |
