# P082 網頁平台建置、移轉與停用規格書

版本：2026-09-06 移轉快照

## 1. 文件目的與範圍

本文件說明如何保管、重建、驗證及重新部署從舊主機移出的 P082 平台。儲存庫包含 `/var/www/html` 入口、P082 PHP 前端、Python 影像運算工作程式、歷史 `project_template`、`petang_20230110` 解析程式、部分 GDAL 工具及 Apache 設定快照。

本快照不包含模型權重、病患或使用者上傳資料、帳號資料庫、產生結果、大型地圖磚及大型研究資料。這些項目不得直接加入 GitHub；若日後需要恢復，應由資料擁有者經授權後透過加密的私有物件儲存或模型登錄服務另行提供。

## 2. 系統架構與資料流

1. Apache 提供 `/var/www/html`，其中 `P082`、`project_template` 與 `petang_20230110` 原本是指向 `/home/ACCOUNT/...` 的符號連結。
2. PHP 前端接受使用者登入、上傳與工作請求，並把工作狀態寫入 `img_view_cut_computing`、`lab_computing` 等目錄。
3. `source/apps/P082/run_cron.sh` 每 10 秒執行影像切割及寄生蟲卵辨識工作。
4. Python 工作程式使用 SSD、U-Net 與 Faster R-CNN 相關程式和外部模型權重，結果寫入 `webserver/user` 與結果／錯誤目錄。
5. `project_template` 與 `petang_20230110` 是歷史應用，應與主要服務分開評估；不需要時不要對外發布。

## 3. 移轉內容

```text
source/webroot/                  /var/www/html 的入口檔
source/apps/P082/webserver/      P082 PHP 與靜態前端
source/apps/P082/computing_node/ Python 運算工作程式
source/apps/project_template/    歷史教學／地圖應用
source/apps/petang_20230110/     歷史解析程式
source/tools/gdal2tiles_test/    小型原始碼檔；資料已排除
source/config/apache2/           原 Apache 設定的去識別化快照
environment/                     主機、套件與執行環境盤點
deploy/                          較安全的範例設定
```

完整來源對照見 `environment/source-map.txt`；排除項目及原始大小見 `environment/excluded-assets.tsv`。較大的排除項目包括 8.5 GB `Pre_train_data`、6.6 GB 模型 checkpoints、14 GB `ITM001` 地圖資料、18 GB `gdal2tiles_test` 資料，以及約 4.4 GB petang 輸出／研究檔。

## 4. 原主機精確環境盤點

| 元件 | 擷取到的版本或狀態 |
|---|---|
| 作業系統 | Ubuntu 20.04.1 LTS，Linux 5.15.0-139-generic，x86_64 |
| CPU／記憶體 | Intel Core i5-7600，4 核心；61 GiB RAM；2 GiB swap |
| Apache | 2.4.41 (Ubuntu)，prefork MPM，`libapache2-mod-php7.0`，僅 HTTP/80 |
| PHP | 7.0.33，Sury 套件；Zend OPcache |
| PHP 模組 | calendar、ctype、dom、exif、fileinfo、ftp、gd、gettext、json、openssl、PDO、sockets、XML、XSL、zip 等；完整清單見 `environment/php.txt` |
| 系統 Python | 3.8.10；未發現系統 pip |
| Conda base | Python 3.8.3，conda 4.8.4 |
| `gdal-env` | Python 3.6.13、GDAL 3.3.1、NumPy 1.19.5、Pillow 5.2.0 |
| `tf-1.0.0-gpu` | Python 3.5.6、tensorflow-gpu 1.0.0、Keras 2.2.2、NumPy 1.14.5、SciPy 0.19.1、OpenCV 3.4.2.17、scikit-image 0.13、scikit-learn 0.19 |
| CUDA | 8.0.61，`/usr/local/cuda -> /usr/local/cuda-8.0` |
| cuDNN | 5.1.10，`libcudnn.so.5.1.10` |
| NVIDIA 驅動 | 套件版本 450.119.03；盤點時 `nvidia-smi` 無法連線到驅動，GPU 型號無法確認 |

精確清單：`conda-*.yml` 為 Conda 匯出、`conda-*-explicit.txt` 為精確套件 URL、`pip-*-freeze.txt` 為 pip 清單、`relevant-debian-packages.tsv` 為相關 Debian 套件。舊套件可能已從公開鏡像移除；explicit 清單僅供取證重現，不代表來源仍可用或安全。

## 5. 安全與授權前置條件

公開或重新上線前必須完成下列工作：

1. 立即撤銷並輪替舊 SSH／sudo 密碼、API key、教職員及使用者密碼；Git 歷史不得包含真實值。
2. 修正已發現的 shell 命令注入、任意檔案刪除與路徑驗證問題；不得只靠輸入過濾字串。
3. Apache/PHP 與 Python worker 使用不同的低權限帳號，均不得擁有 sudo；工作目錄採最小讀寫權限。
4. 帳號檔不得位於可下載的 DocumentRoot。若舊程式尚未重構，至少用 Apache `<FilesMatch>` 阻擋並限制檔案為 `0640`。
5. `project_template` 的舊式明文教師密碼流程必須改為 `password_hash()`／`password_verify()`，不得把 `teacher.example.json` 當正式帳號庫。
6. 強制 HTTPS、Secure/HttpOnly/SameSite cookie、CSRF 防護、上傳副檔名與 MIME 驗證、檔名正規化、大小限制與惡意檔掃描。
7. 確認所有自有與第三方程式碼的著作權和授權相容性；在未確認前，GitHub 儲存庫應保持 Private。詳見 `LICENSE-NOTICE.md`。
8. 執行祕密掃描及歷史掃描，例如 Gitleaks；若祕密曾提交，必須重寫歷史並再次輪替。

## 6. 建議的主機與帳號設計

為相容舊版 GPU 套件，建議把系統放在隔離 VM／內網節點，不直接暴露網際網路。前端與 GPU worker 最好拆成兩個容器或 VM；若必須單機：

```bash
sudo addgroup --system p082
sudo adduser --system --home /srv/p082 --ingroup p082 --shell /usr/sbin/nologin p082-worker
sudo usermod -aG p082 www-data
sudo install -d -o root -g p082 -m 0750 /srv/p082
sudo install -d -o www-data -g p082 -m 2770 /srv/p082/runtime
```

不要建立可登入且可 sudo 的共用服務帳號。正式機密使用 root-only secrets file、systemd credential 或秘密管理服務，值由部署流程注入；`ACCOUNT`、`PASSWORD`、`PASSWORD_HASH`、`API_KEY` 只是占位符。

## 7. Apache 與 PHP 建置

### 7.1 相容性建置

PHP 7.0 已停止安全維護，Ubuntu 20.04 也不是新部署的理想基線。若只為取證相容，可建立無外網入口的 Ubuntu 20.04 VM，使用封存或受控套件庫安裝 Apache 2.4、PHP 7.0 及 `gd/xml/xsl/zip` 等模組。套件來源必須驗證簽章並記錄；不要在公網生產機直接使用過期 PHP。

```bash
sudo apt update
sudo apt install apache2 libapache2-mod-security2
# 從受控／封存來源安裝與 environment/php.txt 相符的 PHP 7.0 套件
sudo a2enmod headers rewrite ssl security2
apache2ctl -M
php -v
php -m
```

較長期方案是逐頁修正 PHP 8.x 不相容語法、停用動態 shell 呼叫並加入自動測試後，改用仍受支援的 PHP。這不是原碼的直接替換升級。

### 7.2 部署檔案

```bash
sudo rsync -a --delete source/apps/P082/ /srv/p082/P082/
sudo rsync -a source/apps/project_template/ /srv/p082/project_template/
sudo rsync -a source/apps/petang_20230110/ /srv/p082/petang_20230110/
sudo install -m 0644 source/webroot/index.php /var/www/html/index.php
sudo ln -s /srv/p082/P082/webserver /var/www/html/P082
sudo ln -s /srv/p082/project_template /var/www/html/project_template
sudo ln -s /srv/p082/petang_20230110 /var/www/html/petang_20230110
sudo chown -R root:p082 /srv/p082
sudo find /srv/p082 -type d -exec chmod 0750 {} +
sudo find /srv/p082 -type f -exec chmod 0640 {} +
```

建立 worker 和 Apache 確實需要的可寫目錄，逐一設定為 `2770`；不要對整棵程式樹使用 `777`。將 `deploy/apache/p082.conf.example` 複製到 `/etc/apache2/sites-available/p082.conf`，調整 ServerName、路徑及 TLS 後：

```bash
sudo a2ensite p082
sudo apache2ctl configtest
sudo systemctl reload apache2
```

正式環境須把 HTTP 重新導向 HTTPS，並加入 HSTS、內容安全政策及適當的上傳限制。

## 8. 帳號資料建立

P082 範例帳號格式位於 `source/apps/P082/webserver/user/log_in_info.example.txt`。正式檔應移出 DocumentRoot，再由程式設定讀取安全路徑。若暫時維持舊格式，產生 bcrypt 雜湊：

```bash
php -r 'echo password_hash("CHANGE_ME", PASSWORD_BCRYPT), PHP_EOL;'
sudo install -o root -g www-data -m 0640 /dev/null /etc/p082/log_in_info.txt
```

將 `ACCOUNT:PASSWORD_HASH` 寫入安全檔，禁止將實際密碼或雜湊提交到 Git。更好的做法是使用資料庫、唯一 salt 的 password hashing、密碼重設及稽核記錄。`project_template/login/teacher.example.json` 與 `teacheraccount.example.txt` 僅描述格式，不可用於正式登入。

## 9. Python、CUDA 與模型環境

### 9.1 建立 Conda 環境

從可信任的 Miniconda／Miniforge 安裝來源建立獨立環境。原環境可嘗試：

```bash
conda env create -n tf-1.0.0-gpu -f environment/conda-tf-1.0.0-gpu.yml
conda env create -n gdal-env -f environment/conda-gdal-env.yml
conda list -n tf-1.0.0-gpu
conda list -n gdal-env
```

若舊 channel 套件不可取得，應在隔離環境依 `*-explicit.txt` 建立內部套件鏡像；不要從不明網站下載二進位檔。Python 3.5 與 TensorFlow 1.0 都已停止維護，建議把模型推論封裝成不對外的固定映像，另規劃升級與模型驗證。

### 9.2 CUDA 8 / cuDNN 5.1

CUDA 與 cuDNN 必須依 NVIDIA 授權從官方或組織保存的合法安裝媒體取得。安裝後確認：

```bash
readlink -f /usr/local/cuda
/usr/local/cuda/bin/nvcc --version
ldconfig -p | grep cudnn
nvidia-smi
```

只有 `nvidia-smi` 正常、驅動與 CUDA 相容後才啟動 worker。原主機此檢查失敗，因此不能宣稱 GPU 環境可用。必要時設定服務的 `PATH=/usr/local/cuda/bin:...` 與 `LD_LIBRARY_PATH=/usr/local/cuda/lib64`，不要全域覆寫未知系統值。

### 9.3 編譯與模型檔

Faster R-CNN 的 Cython 擴充應在相符環境中編譯：

```bash
cd /srv/p082/P082/computing_node/img_parasite_egg_detection/multi_fast_rcnn/lib/utils
conda run -n tf-1.0.0-gpu python setup.py build_ext --inplace
```

模型與資料未放在儲存庫。經授權取得後，依 `kind_link_model.json` 的檔名放入下列路徑並記錄 SHA-256：

- `.../ssd_fordel/checkpoints/`
- `.../u_net/checkpoints/`
- `.../multi_fast_rcnn/checkpoints/version1/`
- `.../multi_fast_rcnn/checkpoints/version2/`
- `source/apps/P082/webserver/Pre_train_data/`（若功能確實需要）

模型不可從來源不明的位置抓取；含個資或使用者產生內容的資料不可用公開 Git LFS 取代。

## 10. Worker 服務

先將程式內 `/home/ACCOUNT` 路徑改成正式 `/srv/p082` 或改由設定檔／環境變數提供，並調整 `run_cron.sh` 使用 Conda 環境。複製及校正 `deploy/systemd/p082-workers.service.example` 後：

```bash
sudo install -m 0644 deploy/systemd/p082-workers.service.example /etc/systemd/system/p082-workers.service
sudo systemctl daemon-reload
sudo systemctl enable --now p082-workers
sudo systemctl status p082-workers
sudo journalctl -u p082-workers -n 100 --no-pager
```

確認 `ReadWritePaths` 僅列出實際工作目錄。服務帳號不得 sudo，且不得讀取 SSH key、shell history 或系統密碼檔。

## 11. 驗收測試

上線前至少執行：

```bash
apache2ctl configtest
find /srv/p082 -name '*.php' -print0 | xargs -0 -n1 php -l
curl -fsS http://127.0.0.1/P082/ >/dev/null
curl -I http://127.0.0.1/P082/user/log_in_info.txt   # 應為 403/404
curl -I http://127.0.0.1/project_template/login/teacher.json # 應為 403/404
```

另需測試：錯誤登入、合法登入、上傳限制、佇列建立、worker 取件、每種模型的已知測試影像、結果輸出、併發、磁碟滿載、worker 重啟、備份還原及權限越界。GPU 測試需確認 TensorFlow 可載入模型並且未退回 CPU。所有測試資料都應為去識別化樣本。

## 12. 備份、回復與停機

備份至少包含：Git commit、部署設定、私有模型清單及 SHA-256、秘密管理系統中的設定、資料庫／帳號資料的加密備份，以及 runtime 資料。每次備份都要做實際還原演練。

回復順序：建立乾淨主機 → 安裝固定版本 → checkout 指定 commit → 還原私有設定／模型／資料 → 建立權限 → 語法與安全檢查 → 只在 localhost 測試 → 開啟 TLS 流量。

只有在系統擁有者另行核准正式除役時，才對舊服務執行：

```bash
sudo systemctl stop apache2
sudo systemctl disable apache2
systemctl is-active apache2
systemctl is-enabled apache2
ss -lntp | grep ':80 '
```

除役後預期狀態為 `inactive`、`disabled`，且沒有程序監聽 80。未取得明確核准時不得執行。本次只準備 GitHub 程式碼，來源服務維持運作。停機不等於刪除；原機仍應限制網路存取、輪替舊憑證並依資料保留政策安全封存或清除。

## 13. 編碼注意事項

部分 `project_template` 舊檔可能使用 Big5 或已含亂碼。移轉時先保留原始 bytes；確認編碼後再用 `iconv` 產生 UTF-8 副本並逐頁比對，避免以錯誤編碼批次覆寫造成不可逆資料損壞。

## 14. 建議現代化路線

短期把舊 GPU worker 與 Web 前端隔離並封住外網；中期移除 shell 呼叫、改用正式工作佇列和資料庫、加入單元／整合測試；長期遷移至受支援的 PHP、Python、TensorFlow/CUDA 版本並重新驗證模型輸出。任何升級都要用固定測試影像比較數值與偵測結果，不能只以「能啟動」作為成功標準。
