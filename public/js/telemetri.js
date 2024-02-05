const getRequest = async (url) => {
    try {
        const response = await axios.get(url, {
            "Accept": "application/json"
        });

        const data = await response.data

        return [data, null]
    } catch (error) {
        return [null, error]
    }
}
