## build the image

```bash
docker build -t flare-bank .
```

## run the image

```bash
docker run -p 80:80 --name flare-bank-container flare-bank
```

## hadolint

```bash
hadolint --config hadolint.yaml Dockerfile
```
