# Buy or Bye(衝動買い防止アプリ)

ユーザーが欲しがっているもの(以下、「**気になるもの**」と表現します)を「保留中」「購入済」「見送り」の3つのステータスで管理できる、衝動買い防止アプリです。

ステータス別に件数や合計金額を集計して、購入を見送った金額の可視化や実績に応じたメダルの獲得を通じて、お金を使いすぎない習慣づくりを支援するものです。

---

## スクリーンショット

<img width="2936" height="1662" alt="login" src="https://github.com/user-attachments/assets/bd309c55-8fcf-42c3-82f1-1a4ca96a88b3" />
<img width="2942" height="1670" alt="top" src="https://github.com/user-attachments/assets/e788e947-8da6-4dc8-9ca2-b48b2d6e538c" />
<img width="2938" height="1670" alt="account-delete" src="https://github.com/user-attachments/assets/0f2b29b4-8c44-48b8-8247-0310bf131442" />
<img width="300" alt="sp-login" src="https://github.com/user-attachments/assets/94f976d7-0445-439a-b8d3-24aa47d6704f" />
<img width="300" alt="sp-top" src="https://github.com/user-attachments/assets/3e5ce65b-3167-4094-a231-8d5c4dce4aff" />

---

## 使用技術(2026年11月7日時点)

- バックエンド: PHP 8.5.12 / Laravel 12.69.2
- データベース: PostgreSQL 18
- フロントエンド: Blade / Tailwind CSS 4.3.3 / Alpine.js 3.17.5 / JavaScript
- テスト: Pest 4
- 開発環境: Docker (Laravel Sail)

---

## 機能一覧

### ユーザー認証機能

- ログイン(メールアドレス、パスワード)
- ゲストログイン
- ユーザー登録(ユーザー名、メールアドレス、パスワード)
    - ゲストユーザーのセッションが有効なうちに登録した場合、データ引き継ぎ

### 「気になるもの」CRUD機能

- 作成、編集、詳細、削除

### 集計結果の表示

- 購入を見送った金額
    - 今週分と累計
- 保留中の金額
    - 累計金額
- 購入済の金額
    - 今週分の合計

### 実績機能

- 条件達成時にメダルを付与。フラッシュメッセージにて通知。

### 設定画面

- ユーザー情報の確認(ユーザー名、メールアドレス)
- ユーザー情報の変更(ユーザー名、メールアドレスを変更でき、パスワード入力が必要)
- アカウント削除(パスワード入力が必要)

---

## テスト

### 自動テスト

PestによるFeatureテストを行いました。主な実施内容は以下の通りです。

- 各ページの表示
- 認証機能(ゲストログイン含む)
- 「気になるもの」のCRUD
- 集計機能
- 実績機能
- アカウント情報更新
- アカウント削除
