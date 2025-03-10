import WhyCard from "./WhyCard"

const Why = () => {

    const whyData = [
        {
            "title": "Quick & Hassle-free",
            "desc": "Every product you buy from SharePal is fully tested and quality checked before delivery",
            "image_name": ""
        },
        {
            "title": "Big Savings",
            "desc": "Save more than 50% on buying pre-owned gadgets.",
            "image_name": ""
        },
        {
            "title": "Easy Refund",
            "desc": "After Cancellation get easy process to refund",
            "image_name": ""
        },
        {
            "title": "Safe & Sanitised",
            "desc": "Enjoy peace of mind with our Safe & Sanitised product guarantee",
            "image_name": ""
        },
    ]

    return <>
        <div className="text-center p-10 bg-gray-200 ">
            <h2 className="font-bold  text-4xl">Why ClickTurf</h2>
            <div className="flex flex-wrap justify-evenly">
                {
                     whyData.map((data, index) => <WhyCard key={index} data={data} /> )
                }
                
            </div>
        </div>
    </>
}

export default Why