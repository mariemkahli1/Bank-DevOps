## build the image

```bash
docker build -t your-image-name .
```

## run the image

```bash
docker run -p 80:80 --name my-php-container your-image-name
```

## hadolint

```bash
hadolint --config hadolint.yaml Dockerfile
```
