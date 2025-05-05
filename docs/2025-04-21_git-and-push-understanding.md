# 【2025-04-21】GitとPushの理解メモ

## ✅ 今日の疑問
Gitのcommitとpushの違いがよくわからなかった。

## ✅ わかったこと

- commit：ローカルでの保存
- push：GitHubにアップロード（バックアップ・共有目的）

## ✅ イメージ図
ブランチ構造（ツリーイメージ）
main
├─● 初期コミット（Laravel Breeze導入）
├─● 記事投稿機能作成
├─● README仮置き ← GitHubにpush済み
└──┬─ feature/readme-update（作業ブランチ）
   ├─● README正式版作成
   └─● コメント方針を追記

コミットとpushの関係図（1行ずつ進む感じ）
[編集作業]
   ↓
git add
   ↓
git commit  ← ローカルで履歴が残る
   ↓
git push    ← GitHubにアップロード

## ✅ざっくりイメージ 
🚀 commit は「ローカルにセーブ」
☁️ push は「GitHubにアップロード」

## ✅ 今日の気づき

- READMEを空でpushしても、あとから更新して問題なし
- GitKraken入れたらツリー構造の理解が一気に進んだ！

## ✅ 次にやること

- README完成形をpush
- Bladeテンプレートのレイアウト構造を整理する
