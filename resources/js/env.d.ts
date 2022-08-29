/// <reference types="vite/client" />

interface ImportMetaEnv {
    readonly VITE_KEY_PATH: string
    // more env variables...
}

interface ImportMeta {
    readonly env: ImportMetaEnv
}
