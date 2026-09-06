# P082 Web Platform — sanitized migration snapshot

This repository is a sanitized source-code snapshot of the legacy P082 parasite-egg analysis platform and related historical web applications.

本儲存庫是舊版 P082 寄生蟲卵影像分析平台及相關歷史網頁程式的去識別化原始碼快照。

## Documentation / 文件

- [繁體中文建置與移轉規格書](docs/DEPLOYMENT.zh-TW.md)
- [English deployment and migration specification](docs/DEPLOYMENT.en.md)
- [Security and disclosure notes](SECURITY.md)
- [Captured environment inventory](environment/)
- [Excluded large/private assets](environment/excluded-assets.tsv)

## Repository layout

```text
source/
  webroot/                     original /var/www/html entry files
  apps/P082/                   PHP front end and Python ML workers
  apps/project_template/       historical A-TEAM teaching application
  apps/petang_20230110/        petang parser source
  tools/gdal2tiles_test/       retained GDAL helper source only
  config/apache2/              sanitized Apache configuration snapshot
environment/                   exact package and hardware inventory
deploy/                        safer example Apache/systemd configuration
docs/                          bilingual deployment specifications
```

Large model weights, map tiles, user uploads, generated results, account databases, and research datasets are intentionally excluded. `ACCOUNT`, `PASSWORD`, `PASSWORD_HASH`, and `API_KEY` are non-secret placeholders only.

大型模型權重、地圖磚、使用者上傳檔、產生結果、帳號資料庫及研究資料均刻意排除。`ACCOUNT`、`PASSWORD`、`PASSWORD_HASH` 與 `API_KEY` 僅為非機密占位符。

## Important status

The captured GPU stack is legacy: CUDA 8.0.61, cuDNN 5.1.10, Python 3.5.6, TensorFlow GPU 1.0.0, and Keras 2.2.2. The source host's NVIDIA driver was not operational at capture time. Treat this exact stack as a forensic compatibility target, not a recommended internet-facing production baseline.

The original source did not contain a verified project-wide license. Review [LICENSE-NOTICE.md](LICENSE-NOTICE.md) before making the repository public.

The source host remains in service. This repository is preparation material only and does not authorize publication or decommissioning.
