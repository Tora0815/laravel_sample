2025-05-07 Ajaxページネーションの実装と不具合の原因解消
✅ 今日やったこと
完全なAjaxページネーションの仕組みを導入

UserPicsController::getpics() を Ajaxと通常リクエストで分岐

Bladeファイル list_only.blade.php の .page_bt 対応ボタン形式に修正

app.js に changeContents(page) を実装、.page_bt に .on("click") バインド追加

FormData で u_id / page を送信し、差し替えHTMLをレスポンスで更新

コンソールログ (console.log) と Laravelログ (Log::debug) の併用でデバッグ

Laravelの storage/logs/laravel.log に書き込めないエラー → パーミッション修正

Vite のビルドエラー → node_modules 削除 + npm install で復旧

リクエストが来ているのにページ遷移しない原因を調査

📘 学んだこと・気づき
原因は JavaScript 側で page 値をそのまま使っていたこと
Laravelの paginate() は 1始まり だが、 .page_bt ボタンの value は 0始まり だった
→ Number(page) + 1 に変換すれば正しくページ切り替えできた

Laravelコントローラで request->ajax() を使えば、HTMLとAjaxの分岐処理が自然に書ける

JSのイベントは .on("click", ".page_bt", ...) のように動的要素に対して document にバインドしないと動かない

Laravelのログ書き込み権限 (storage/logs) に注意。Dockerコンテナの中でchownして解決

console.log() が出ない場合は JSが読み込まれてない or Vite未ビルドの可能性あり

🔜 明日やること
モーダルウインドウの修正

💬 感想（任意）
ずっと原因がわからなかったページ切り替えの不具合、結局 page のズレ（0始まり vs 1始まり）という初歩的な罠だった。でもそこに気づくために、JS・Blade・Controller・Laravelログ・ブラウザ検証の全部を見ていく必要があって、Ajaxページネーションの仕組み全体を深く理解できた気がする。地味だけどかなり成長を感じた一日だった！