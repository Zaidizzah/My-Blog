class UploadPreview {
    constructor(inputElement, options = {}) {
        // Konversi ukuran file yang lebih fleksibel
        this.parseSize = (size) => {
            if (typeof size === "number") return size;

            const units = {
                B: 1,
                KB: 1024,
                MB: 1024 * 1024,
                GB: 1024 * 1024 * 1024,
            };

            const match = size.match(/^(\d+)([BKMG]B?)$/i);
            if (!match)
                throw new Error(
                    'Format ukuran tidak valid. Gunakan format seperti "2MB", "500KB".'
                );

            const value = parseInt(match[1]);
            const unit = match[2].toUpperCase();

            return value * units[unit];
        };

        // Validasi default
        this.defaultOptions = {
            maxSize: "5MB",
            maxWidth: 3000,
            maxHeight: 3000,
            minWidth: 50,
            minHeight: 50,
            typeAllows: [
                "image/jpeg",
                "image/jpg",
                "image/png",
                "image/gif",
                "image/webp",
                "image/svg+xml",
            ],
        };

        // Merge opsi default dengan opsi yang diberikan
        this.options = {
            ...this.defaultOptions,
            ...options,
            maxSize: this.parseSize(
                options.maxSize || this.defaultOptions.maxSize
            ),
        };

        this.inputElement = document.querySelector(inputElement);
        this.container = null;
        this.errorContainer = null;

        this.init();
    }

    init() {
        // Wrap input dengan container
        this.container = document.createElement("div");
        this.container.classList.add("image-preview-container");
        this.inputElement.parentNode.insertBefore(
            this.container,
            this.inputElement
        );

        // Tambahkan error container
        this.errorContainer = document.createElement("div");
        this.errorContainer.classList.add("image-preview-errors");
        this.container.appendChild(this.errorContainer);

        // Tambahkan input ke container
        this.container.appendChild(this.inputElement);

        // Tambahkan event listener
        this.inputElement.addEventListener(
            "change",
            this.handleFileChange.bind(this)
        );
    }

    createPreviewElements() {
        // Bersihkan preview sebelumnya jika ada
        const existingPreview = this.container.querySelector(".image-preview");
        const existingMetadata =
            this.container.querySelector(".image-metadata");
        const existingResetButton = this.container.querySelector(
            ".image-preview-reset"
        );

        if (existingPreview) existingPreview.remove();
        if (existingMetadata) existingMetadata.remove();
        if (existingResetButton) existingResetButton.remove();

        // Buat preview image
        this.previewImg = document.createElement("img");
        this.previewImg.classList.add("image-preview");

        // Buat kontainer metadata
        this.metadataContainer = document.createElement("div");
        this.metadataContainer.classList.add("image-metadata");

        // Buat tombol reset/close
        this.resetButton = document.createElement("button");
        this.resetButton.textContent = "×";
        this.resetButton.classList.add("image-preview-reset");
        this.resetButton.addEventListener(
            "click",
            this.resetPreview.bind(this)
        );

        // Tambahkan elemen ke container
        this.container.appendChild(this.previewImg);
        this.container.appendChild(this.metadataContainer);
        this.container.appendChild(this.resetButton);
    }

    async handleFileChange(event) {
        // Bersihkan error sebelumnya
        this.clearErrors();

        const file = event.target.files[0];
        if (!file) return;

        try {
            // Validasi file
            const validationErrors = await this.validateFile(file);

            if (validationErrors.length > 0) {
                this.showErrors(validationErrors);
                this.inputElement.value = ""; // Reset input
                return;
            }

            // Buat preview
            this.createPreviewElements();

            // Baca dan tampilkan gambar
            const reader = new FileReader();
            reader.onload = (e) => {
                this.previewImg.src = e.target.result;
                this.updateMetadata(file);
            };
            reader.readAsDataURL(file);
        } catch (error) {
            this.showErrors([error.message]);
        }
    }

    async validateFile(file) {
        const errors = [];

        // Validasi tipe file
        if (!this.options.typeAllows.includes(file.type)) {
            errors.push(
                `Tipe file tidak diizinkan. Hanya ${this.options.typeAllows.join(
                    ", "
                )} yang diperbolehkan.`
            );
        }

        // Validasi ukuran file
        if (file.size > this.options.maxSize) {
            errors.push(
                `Ukuran file maksimal ${this.formatBytes(
                    this.options.maxSize
                )}.`
            );
        }

        // Validasi dimensi gambar
        return new Promise((resolve) => {
            const img = new Image();
            img.onload = () => {
                if (img.width > this.options.maxWidth) {
                    errors.push(
                        `Lebar gambar maksimal ${this.options.maxWidth}px.`
                    );
                }
                if (img.height > this.options.maxHeight) {
                    errors.push(
                        `Tinggi gambar maksimal ${this.options.maxHeight}px.`
                    );
                }
                if (img.width < this.options.minWidth) {
                    errors.push(
                        `Lebar gambar minimal ${this.options.minWidth}px.`
                    );
                }
                if (img.height < this.options.minHeight) {
                    errors.push(
                        `Tinggi gambar minimal ${this.options.minHeight}px.`
                    );
                }
                resolve(errors);
            };
            img.onerror = () => resolve([...errors, "Gagal memuat gambar"]);
            img.src = URL.createObjectURL(file);
        });
    }

    showErrors(errors) {
        // Bersihkan error sebelumnya
        this.clearErrors();

        // Tampilkan pesan kesalahan
        errors.forEach((error) => {
            const errorElement = document.createElement("p");
            errorElement.textContent = error;
            this.errorContainer.appendChild(errorElement);
        });

        // Tampilkan error container
        this.errorContainer.style.display = "block";

        // Sembunyikan error setelah 5 detik
        setTimeout(() => {
            this.clearErrors();
        }, 5000);
    }

    clearErrors() {
        // Bersihkan isi error container
        this.errorContainer.innerHTML = "";
        // Sembunyikan error container
        this.errorContainer.style.display = "none";
    }

    updateMetadata(file) {
        // Bersihkan metadata sebelumnya
        this.metadataContainer.innerHTML = "";

        // Tambahkan metadata
        const metadataItems = [
            `Nama: ${file.name}`,
            `Tipe: ${file.type}`,
            `Ukuran: ${this.formatBytes(file.size)}`,
        ];

        metadataItems.forEach((item) => {
            const metaItem = document.createElement("p");
            metaItem.textContent = item;
            this.metadataContainer.appendChild(metaItem);
        });
    }

    resetPreview() {
        // Reset input
        this.inputElement.value = "";

        // Bersihkan preview
        this.createPreviewElements();

        // Hapus preview, metadata, dan tombol reset
        const elementsToRemove = [
            this.container.querySelector(".image-preview"),
            this.container.querySelector(".image-metadata"),
            this.container.querySelector(".image-preview-reset"),
        ];

        elementsToRemove.forEach((el) => {
            if (el) el.remove();
        });

        // Reset referensi
        this.previewImg = null;
        this.metadataContainer = null;
        this.resetButton = null;
    }

    formatBytes(bytes, decimals = 2) {
        if (bytes === 0) return "0 Bytes";

        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ["Bytes", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"];

        const i = Math.floor(Math.log(bytes) / Math.log(k));

        return (
            parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + " " + sizes[i]
        );
    }
}
