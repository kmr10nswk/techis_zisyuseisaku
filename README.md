## TRPG本検索システム

### 概要
このシステムでは、TRPGに関する本の検索や閲覧を行うことが出来ます。
一般ユーザーと管理者ユーザーに分かれており、一般ユーザーは本を所持していることをボタン一つで表せます。
管理者ユーザーは一般ユーザーの削除、管理者ユーザーの登録・削除・権限の変更、商品の管理が可能です。

### 主な機能
【共通】
* ユーザー一覧と詳細の閲覧
* カード型とテーブル型に分かれた商品一覧と詳細の閲覧
* 細かく分類分けされた検索システム
【一般ユーザー】
* 所持済みボタン
【管理者ユーザー】
* 一般ユーザーの削除
* 管理者ユーザーの管理
* 商品の新規登録、変更、削除

### 開発環境
```
PHP 8.3
MySQL 8.0
Laravel 10
```
### 設計書
[設計書ページへ](https://docs.google.com/spreadsheets/d/1E85HU6zMW3Bc3RNEOs1tbDJNBD0HABIZ/edit?usp=drive_link&ouid=107675818066869907379&rtpof=true&sd=true)

### システム閲覧
[アプリケーションページへ](https://item-management-kimura-a53060a9dc04.herokuapp.com/register)

### テストアカウント情報
```
【一般ユーザー】
ID：testdata
Email：test@example.com
PW：testpassword

【管理者ユーザー】
Email：admintest@example.com
PW：admintest
```