# [Helminth egg analysis platform (HEAP): An opened platform for microscopic helminth egg identification and quantification based on the integration of deep learning architectures](https://pubmed.ncbi.nlm.nih.gov/34511389/)

Following the completion of the research project, the hosted HEAP service has been discontinued; to preserve the project's research value and support reproducibility and future development, we are making this sanitized source-code snapshot of the P082 parasite-egg analysis platform and its technical documentation available to researchers who may find it useful. The associated paper is available at [https://pubmed.ncbi.nlm.nih.gov/34511389/](https://pubmed.ncbi.nlm.nih.gov/34511389/).

**中文翻譯（Translation）：** 本研究計畫完成後，HEAP 線上服務已停止；為保存本計畫的研究價值，並支持研究成果的重現與後續發展，我們將 P082 寄生蟲卵影像分析平台的去識別化原始碼快照與技術文件提供給有需要的研究人員參考及使用。

## Licensing / 授權

Unless otherwise stated, original documentation and images created and owned by the HEAP project are licensed under the [Creative Commons Attribution 4.0 International License (CC BY 4.0)](https://creativecommons.org/licenses/by/4.0/). This Creative Commons license does not apply to source code, executable software, model weights, datasets, or third-party components; those materials remain subject to their respective license terms and notices. See [LICENSE-NOTICE.md](LICENSE-NOTICE.md) for details.

除另有註明外，由 HEAP 計畫原創並擁有權利的文件與圖片，採用[創用 CC 姓名標示 4.0 國際授權條款（CC BY 4.0）](https://creativecommons.org/licenses/by/4.0/deed.zh-hant)授權。本創用 CC 授權不適用於原始碼、可執行軟體、模型權重、資料集或第三方元件；該等內容仍應依其各自的授權條款及聲明使用。詳細資訊請參閱 [LICENSE-NOTICE.md](LICENSE-NOTICE.md)。

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
